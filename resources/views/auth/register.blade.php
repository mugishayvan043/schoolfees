@extends('layouts.app')

@section('title', 'Create Account - School Fee Management System')

@section('content')
<div class="login-page">
    <div class="login-panel">
        <div class="login-copy">
            <span class="brand-icon mb-3"><i class="bi bi-person-plus"></i></span>
            <h1>Create Account</h1>
            <p>Register an administrator or accountant account to access the School Fee Management System.</p>
        </div>
        <form method="POST" action="{{ route('register.store') }}" class="login-card">
            @csrf
            <h2 class="h4 mb-4">Registration</h2>
            <div class="mb-3">
                <label class="form-label">Full Name</label>
                <input name="name" value="{{ old('name') }}" class="form-control form-control-lg @error('name') is-invalid @enderror" required autofocus>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" value="{{ old('email') }}" class="form-control form-control-lg @error('email') is-invalid @enderror" required>
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control form-control-lg @error('password') is-invalid @enderror" minlength="5" required>
                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Confirm Password</label>
                <input type="password" name="password_confirmation" class="form-control form-control-lg" minlength="5" required>
            </div>
            <div class="mb-4">
                <label class="form-label">Role</label>
                <select name="role" class="form-select form-select-lg @error('role') is-invalid @enderror" required>
                    <option value="">Select role</option>
                    <option value="admin" @selected(old('role') === 'admin')>Admin</option>
                    <option value="accountant" @selected(old('role') === 'accountant')>Accountant</option>
                </select>
                @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <button class="btn btn-primary btn-lg w-100" type="submit"><i class="bi bi-check-circle"></i> Create Account</button>
            <div class="d-grid mt-3">
                <a href="{{ route('login') }}" class="btn btn-outline-secondary btn-lg">Back to Login</a>
            </div>
        </form>
    </div>
</div>
@endsection
