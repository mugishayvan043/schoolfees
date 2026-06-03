<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SchoolClassController extends Controller
{
    public function index()
    {
        $classes = SchoolClass::withCount('students')
            ->when(request('search'), fn ($query, $search) => $query->where('class_name', 'like', "%{$search}%"))
            ->latest()
            ->paginate(10);

        return view('classes.index', compact('classes'));
    }

    public function create()
    {
        return view('classes.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'class_name' => ['required', 'string', 'max:100', 'unique:classes,class_name'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        $class = SchoolClass::create($data);
        Log::info('Class created.', ['class_id' => $class->id, 'user_id' => auth()->id()]);

        return redirect()->route('classes.index')->with('success', 'Class created successfully.');
    }

    public function show(string $id)
    {
        $class = SchoolClass::with(['students', 'fees'])->findOrFail($id);

        return view('classes.show', compact('class'));
    }

    public function edit(string $id)
    {
        $class = SchoolClass::findOrFail($id);

        return view('classes.edit', compact('class'));
    }

    public function update(Request $request, string $id)
    {
        $class = SchoolClass::findOrFail($id);
        $data = $request->validate([
            'class_name' => ['required', 'string', 'max:100', 'unique:classes,class_name,'.$class->id],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        $class->update($data);
        Log::info('Class updated.', ['class_id' => $class->id, 'user_id' => auth()->id()]);

        return redirect()->route('classes.index')->with('success', 'Class updated successfully.');
    }

    public function destroy(string $id)
    {
        $class = SchoolClass::findOrFail($id);
        $classId = $class->id;
        $class->delete();
        Log::warning('Class deleted.', ['class_id' => $classId, 'user_id' => auth()->id()]);

        return redirect()->route('classes.index')->with('success', 'Class deleted successfully.');
    }
}
