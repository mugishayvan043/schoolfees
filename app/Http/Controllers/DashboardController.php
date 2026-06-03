<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\SchoolClass;
use App\Models\Student;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $students = Student::with(['schoolClass.fees', 'payments'])->get();
        $totalExpected = $students->sum(fn (Student $student) => $student->totalFee());
        $totalCollected = (float) Payment::sum('amount_paid');
        $studentsWithBalances = $students->filter(fn (Student $student) => $student->balance() > 0)->count();

        $monthlyCollections = Payment::whereYear('payment_date', now()->year)
            ->get()
            ->groupBy(fn (Payment $payment) => $payment->payment_date->month)
            ->map(fn ($payments) => $payments->sum('amount_paid'));

        $collectionSeries = collect(range(1, 12))->map(fn ($month) => (float) $monthlyCollections->get($month, 0));

        $classBalances = SchoolClass::with(['students.payments', 'fees'])->get()->map(function (SchoolClass $class) {
            $fee = (float) optional($class->fees->firstWhere('academic_year', config('fees.current_academic_year')))->amount;
            $expected = $fee * $class->students->count();
            $paid = $class->students->flatMap->payments->sum('amount_paid');

            return [
                'class' => $class->class_name,
                'expected' => $expected,
                'collected' => $paid,
                'outstanding' => max($expected - $paid, 0),
            ];
        });

        $recentPayments = Payment::with(['student.schoolClass', 'recorder'])->latest()->take(8)->get();

        return view('dashboard', compact(
            'totalExpected',
            'totalCollected',
            'studentsWithBalances',
            'collectionSeries',
            'classBalances',
            'recentPayments'
        ))->with([
            'totalStudents' => Student::count(),
            'totalClasses' => SchoolClass::count(),
            'totalPayments' => Payment::count(),
        ]);
    }
}
