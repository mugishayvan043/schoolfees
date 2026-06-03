@extends('layouts.app')
@section('page-title', 'Create Class')
@section('content')
<div class="panel"><div class="panel-header"><h2>New Class</h2></div><form method="POST" action="{{ route('classes.store') }}">@include('classes.form')</form></div>
@endsection
