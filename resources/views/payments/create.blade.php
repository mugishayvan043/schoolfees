@extends('layouts.app')
@section('page-title', 'Record Payment')
@section('content')
<div class="panel">
    <div class="panel-header"><h2>New Payment</h2><span class="badge text-bg-primary">{{ $receiptNumber }}</span></div>
    <form method="POST" action="{{ route('payments.store') }}">
        @csrf
        <input type="hidden" name="payment_token" value="{{ $paymentToken }}">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Student</label>
                <select name="student_id" class="form-select @error('student_id') is-invalid @enderror" required>
                    <option value="">Select student</option>
                    @foreach($students as $student)<option value="{{ $student->id }}" @selected(old('student_id', request('student_id')) == $student->id)>{{ $student->student_code }} - {{ $student->full_name }} ({{ $student->schoolClass->class_name }})</option>@endforeach
                </select>
                @error('student_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-3">
                <label class="form-label">Amount Paid</label>
                <input type="number" min="0.01" step="0.01" name="amount_paid" value="{{ old('amount_paid') }}" class="form-control @error('amount_paid') is-invalid @enderror" required>
                @error('amount_paid')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-3">
                <label class="form-label">Payment Date</label>
                <input type="date" name="payment_date" value="{{ old('payment_date', now()->toDateString()) }}" class="form-control @error('payment_date') is-invalid @enderror" required>
                @error('payment_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label">Payment Method</label>
                <select name="payment_method" class="form-select @error('payment_method') is-invalid @enderror" required>
                    <option value="">Select method</option>
                    @foreach(['Cash','Bank Transfer','Mobile Money','Card','Cheque'] as $method)<option value="{{ $method }}" @selected(old('payment_method') === $method)>{{ $method }}</option>@endforeach
                </select>
                @error('payment_method')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
        <div class="mt-4"><button class="btn btn-primary"><i class="bi bi-receipt"></i> Save and Generate Receipt</button><a href="{{ route('payments.index') }}" class="btn btn-light border">Cancel</a></div>
    </form>
</div>
@endsection
