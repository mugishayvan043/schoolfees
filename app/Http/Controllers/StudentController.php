<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::with('schoolClass')
            ->when(request('search'), function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('student_code', 'like', "%{$search}%")
                        ->orWhere('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%");
                });
            })
            ->when(request('class_id'), fn ($query, $classId) => $query->where('class_id', $classId))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $classes = SchoolClass::orderBy('class_name')->get();

        return view('students.index', compact('students', 'classes'));
    }

    public function create()
    {
        $classes = SchoolClass::orderBy('class_name')->get();

        return view('students.create', compact('classes'));
    }

    public function store(Request $request)
    {
        try {
            $student = Student::create($this->validated($request));
            Log::info('Student created.', ['student_id' => $student->id, 'user_id' => auth()->id()]);
        } catch (\Throwable $exception) {
            Log::error('Student creation failed.', ['error' => $exception->getMessage(), 'user_id' => auth()->id()]);
            throw $exception;
        }

        return redirect()->route('students.index')->with('success', 'Student registered successfully.');
    }

    public function show(string $id)
    {
        $student = Student::with(['schoolClass.fees', 'payments.recorder'])->findOrFail($id);

        return view('students.show', compact('student'));
    }

    public function edit(string $id)
    {
        $student = Student::findOrFail($id);
        $classes = SchoolClass::orderBy('class_name')->get();

        return view('students.edit', compact('student', 'classes'));
    }

    public function update(Request $request, string $id)
    {
        $student = Student::findOrFail($id);
        try {
            $student->update($this->validated($request, $student->id));
            Log::info('Student updated.', ['student_id' => $student->id, 'user_id' => auth()->id()]);
        } catch (\Throwable $exception) {
            Log::error('Student update failed.', ['student_id' => $student->id, 'error' => $exception->getMessage()]);
            throw $exception;
        }

        return redirect()->route('students.show', $student)->with('success', 'Student details updated successfully.');
    }

    public function destroy(string $id)
    {
        $student = Student::findOrFail($id);
        $studentId = $student->id;
        $student->delete();
        Log::warning('Student deleted.', ['student_id' => $studentId, 'user_id' => auth()->id()]);

        return redirect()->route('students.index')->with('success', 'Student deleted successfully.');
    }

    private function validated(Request $request, ?int $studentId = null): array
    {
        return $request->validate([
            'student_code' => ['required', 'string', 'max:50', 'unique:students,student_code'.($studentId ? ','.$studentId : '')],
            'first_name' => ['required', 'string', 'max:100', "regex:/^[A-Za-z][A-Za-z\\s'-]*$/"],
            'last_name' => ['required', 'string', 'max:100', "regex:/^[A-Za-z][A-Za-z\\s'-]*$/"],
            'gender' => ['required', 'in:Male,Female,Other'],
            'class_id' => ['required', 'exists:classes,id'],
            'phone' => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'string', 'max:1000'],
        ]);
    }
}
