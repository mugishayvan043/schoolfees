@extends('layouts.app')
@section('page-title', 'Edit Class')
@section('content')
<div class="panel"><div class="panel-header"><h2>Edit Class</h2></div><form method="POST" action="{{ route('classes.update', $class) }}">@method('PUT') @include('classes.form')</form></div>
@endsection
