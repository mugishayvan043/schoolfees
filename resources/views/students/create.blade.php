@extends('layouts.app')
@section('page-title', 'Register Student')
@section('content')
<div class="panel"><div class="panel-header"><h2>New Student</h2></div><form method="POST" action="{{ route('students.store') }}">@include('students.form')</form></div>
@endsection
