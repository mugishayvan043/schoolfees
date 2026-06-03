@extends('layouts.app')
@section('page-title', $class->class_name)
@section('content')
<div class="row g-4">
    <div class="col-lg-5"><div class="panel"><div class="panel-header"><h2>Class Details</h2></div><p>{{ $class->description ?: 'No description provided.' }}</p><p><strong>Students:</strong> {{ $class->students->count() }}</p><p><strong>Fee Structures:</strong> {{ $class->fees->count() }}</p></div></div>
    <div class="col-lg-7"><div class="panel"><div class="panel-header"><h2>Students</h2></div><table class="table"><thead><tr><th>Code</th><th>Name</th><th>Phone</th></tr></thead><tbody>@foreach($class->students as $student)<tr><td>{{ $student->student_code }}</td><td><a href="{{ route('students.show', $student) }}">{{ $student->full_name }}</a></td><td>{{ $student->phone ?: '-' }}</td></tr>@endforeach</tbody></table></div></div>
</div>
@endsection
