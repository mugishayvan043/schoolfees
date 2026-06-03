@extends('layouts.app')

@section('title', 'Forbidden')
@section('page-title', 'Forbidden')

@section('content')
<div class="error-page panel text-center">
    <h1>403</h1>
    <h2>Access denied</h2>
    <p class="text-muted">You do not have permission to access this page.</p>
    <a href="{{ auth()->check() ? route('dashboard') : route('login') }}" class="btn btn-primary">Go Back</a>
</div>
@endsection
