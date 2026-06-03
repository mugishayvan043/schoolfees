@extends('layouts.app')
@section('page-title', 'Edit Student')
@section('content')
<div class="panel"><div class="panel-header"><h2>Edit Student</h2></div><form method="POST" action="{{ route('students.update', $student) }}">@method('PUT') @include('students.form')</form></div>
@endsection
