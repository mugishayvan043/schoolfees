<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\SchoolClass;
use App\Models\Student;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $data = $this->reportData($request);

        return view('reports.index', $data);
    }

    public function pdf(Request $request)
    {
        try {
            $data = $this->reportData($request);
            $pdf = Pdf::loadView('reports.pdf', $data)->setPaper('a4', 'landscape');

            return $pdf->download('school-fee-report-'.now()->format('Ymd-His').'.pdf');
        } catch (\Throwable $exception) {
            Log::error('PDF report generation failed.', ['error' => $exception->getMessage(), 'user_id' => auth()->id()]);

            return back()->withErrors(['report' => 'The PDF report could not be generated. Please try again.']);
        }
    }

    public function excel(Request $request)
    {
        try {
            $data = $this->reportData($request);
            $html = view('reports.excel', $data)->render();

            return response($html, Response::HTTP_OK, [
                'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
                'Content-Disposition' => 'attachment; filename="school-fee-report-'.now()->format('Ymd-His').'.xls"',
            ]);
        } catch (\Throwable $exception) {
            Log::error('Excel report generation failed.', ['error' => $exception->getMessage(), 'user_id' => auth()->id()]);

            return back()->withErrors(['report' => 'The Excel report could not be generated. Please try again.']);
        }
    }

    private function reportData(Request $request): array
    {
        $filters = $request->validate([
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date', 'after_or_equal:date_from'],
            'class_id' => ['nullable', 'exists:classes,id'],
            'student_id' => ['nullable', 'exists:students,id'],
        ]);

        $classes = SchoolClass::orderBy('class_name')->get();
        $studentsList = Student::orderBy('first_name')->get();

        $students = Student::with(['schoolClass.fees', 'payments' => function ($query) use ($filters) {
            $query->when($filters['date_from'] ?? null, fn ($query, $date) => $query->whereDate('payment_date', '>=', $date))
                ->when($filters['date_to'] ?? null, fn ($query, $date) => $query->whereDate('payment_date', '<=', $date));
        }])
            ->when($filters['class_id'] ?? null, fn ($query, $classId) => $query->where('class_id', $classId))
            ->when($filters['student_id'] ?? null, fn ($query, $studentId) => $query->where('id', $studentId))
            ->orderBy('first_name')
            ->get();

        $payments = Payment::with(['student.schoolClass', 'recorder'])
            ->when($filters['date_from'] ?? null, fn ($query, $date) => $query->whereDate('payment_date', '>=', $date))
            ->when($filters['date_to'] ?? null, fn ($query, $date) => $query->whereDate('payment_date', '<=', $date))
            ->when($filters['class_id'] ?? null, fn ($query, $classId) => $query->whereHas('student', fn ($query) => $query->where('class_id', $classId)))
            ->when($filters['student_id'] ?? null, fn ($query, $studentId) => $query->where('student_id', $studentId))
            ->latest('payment_date')
            ->get();

        $totalExpected = $students->sum(fn (Student $student) => $student->totalFee());
        $totalCollected = $payments->sum('amount_paid');

        return [
            'classes' => $classes,
            'studentsList' => $studentsList,
            'students' => $students,
            'payments' => $payments,
            'filters' => $filters,
            'totalExpected' => $totalExpected,
            'totalCollected' => $totalCollected,
            'outstanding' => max($totalExpected - $students->sum(fn (Student $student) => $student->payments->sum('amount_paid')), 0),
        ];
    }
}
