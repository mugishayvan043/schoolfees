@extends('layouts.app')

@section('title', 'Login - School Fee Management System')

@section('content')
<div class="login-page">
    <div class="login-panel">
        <div class="login-copy">
            <span class="brand-icon mb-3"><i class="bi bi-bank"></i></span>
            <h1>School Fee Management System</h1>
            <p>Digitized school fee collection, receipts, balances, and financial reporting for modern academic offices.</p>
        </div>
        <form method="POST" action="{{ route('login.store') }}" class="login-card">
            @csrf
            <h2 class="h4 mb-4">Secure Login</h2>
            <div class="mb-3">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" value="{{ old('email') }}" class="form-control form-control-lg @error('email') is-invalid @enderror" required autofocus>
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control form-control-lg @error('password') is-invalid @enderror" required>
                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="form-check mb-4">
                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                <label class="form-check-label" for="remember">Remember me</label>
            </div>
            <button class="btn btn-primary btn-lg w-100" type="submit"><i class="bi bi-shield-lock"></i> Login</button>
            <div class="d-grid mt-3">
                <a href="{{ route('register') }}" class="btn btn-outline-primary btn-lg"><i class="bi bi-person-plus"></i> Create Account</a>
            </div>
        </form>
    </div>
</div>
@endsection
