@extends('layouts.app')

@section('title', 'Not Found')
@section('page-title', 'Not Found')

@section('content')
<div class="error-page panel text-center">
    <h1>404</h1>
    <h2>Page or record not found</h2>
    <p class="text-muted">The item you requested could not be found or may have been removed.</p>
    <a href="{{ auth()->check() ? route('dashboard') : route('login') }}" class="btn btn-primary">Go Back</a>
</div>
@endsection
