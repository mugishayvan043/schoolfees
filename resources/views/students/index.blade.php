@extends('layouts.app')
@section('page-title', 'Students')
@section('content')
<div class="panel">
    <div class="panel-header">
        <h2>Student Management</h2>
        <a href="{{ route('students.create') }}" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Add Student</a>
    </div>
    <form class="row g-2 mb-3">
        <div class="col-md-5"><input name="search" value="{{ request('search') }}" class="form-control" placeholder="Search by code or name"></div>
        <div class="col-md-4">
            <select name="class_id" class="form-select">
                <option value="">All classes</option>
                @foreach($classes as $class)<option value="{{ $class->id }}" @selected(request('class_id') == $class->id)>{{ $class->class_name }}</option>@endforeach
            </select>
        </div>
        <div class="col-md-3 d-flex gap-2"><button class="btn btn-outline-primary" type="submit"><i class="bi bi-search"></i> Search</button><a class="btn btn-light border" href="{{ route('students.index') }}">Reset</a></div>
    </form>
    <div class="table-responsive">
        <table class="table align-middle">
            <thead><tr><th>Code</th><th>Name</th><th>Gender</th><th>Class</th><th>Phone</th><th></th></tr></thead>
            <tbody>
            @forelse($students as $student)
                <tr>
                    <td><strong>{{ $student->student_code }}</strong></td>
                    <td>{{ $student->full_name }}</td>
                    <td>{{ $student->gender }}</td>
                    <td>{{ $student->schoolClass->class_name }}</td>
                    <td>{{ $student->phone ?: '-' }}</td>
                    <td class="text-end table-actions">
                        <a href="{{ route('students.show', $student) }}" class="btn btn-sm btn-outline-primary" title="Profile"><i class="bi bi-eye"></i></a>
                        <a href="{{ route('students.edit', $student) }}" class="btn btn-sm btn-outline-secondary" title="Edit"><i class="bi bi-pencil"></i></a>
                        <form method="POST" action="{{ route('students.destroy', $student) }}" onsubmit="return confirm('Delete this student?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger" title="Delete"><i class="bi bi-trash"></i></button></form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center text-muted">No students found.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    {{ $students->links() }}
</div>
@endsection
