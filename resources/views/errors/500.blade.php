@extends('layouts.app')

@section('title', 'Server Error')
@section('page-title', 'Server Error')

@section('content')
<div class="error-page panel text-center">
    <h1>500</h1>
    <h2>Something went wrong</h2>
    <p class="text-muted">The system could not complete the request. The error has been logged for review.</p>
    <a href="{{ auth()->check() ? route('dashboard') : route('login') }}" class="btn btn-primary">Go Back</a>
</div>
@endsection
