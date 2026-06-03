@extends('layouts.app')
@section('page-title', 'Student Profile')
@section('content')
@php($totalFee = $student->totalFee())
@php($paid = $student->payments->sum('amount_paid'))
@php($balance = max($totalFee - $paid, 0))
<div class="row g-4">
    <div class="col-lg-4">
        <div class="panel profile-panel">
            <div class="avatar">{{ strtoupper(substr($student->first_name, 0, 1).substr($student->last_name, 0, 1)) }}</div>
            <h2>{{ $student->full_name }}</h2>
            <p>{{ $student->student_code }} · {{ $student->schoolClass->class_name }}</p>
            <div class="profile-facts">
                <span>Gender <strong>{{ $student->gender }}</strong></span>
                <span>Phone <strong>{{ $student->phone ?: '-' }}</strong></span>
                <span>Address <strong>{{ $student->address ?: '-' }}</strong></span>
            </div>
            <a href="{{ route('students.edit', $student) }}" class="btn btn-primary w-100 mt-3"><i class="bi bi-pencil"></i> Edit Profile</a>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="row g-3 mb-4">
            <div class="col-md-4"><div class="metric-card"><p>Total Fee</p><h3>{{ number_format($totalFee, 2) }}</h3></div></div>
            <div class="col-md-4"><div class="metric-card"><p>Amount Paid</p><h3>{{ number_format($paid, 2) }}</h3></div></div>
            <div class="col-md-4"><div class="metric-card"><p>Balance</p><h3>{{ number_format($balance, 2) }}</h3></div></div>
        </div>
        <div class="panel">
            <div class="panel-header"><h2>Payment History</h2><a href="{{ route('payments.create', ['student_id' => $student->id]) }}" class="btn btn-sm btn-primary">Record Payment</a></div>
            <div class="table-responsive">
                <table class="table"><thead><tr><th>Receipt</th><th>Date</th><th>Method</th><th>Recorded By</th><th>Amount</th></tr></thead><tbody>
                @forelse($student->payments as $payment)
                    <tr><td><a href="{{ route('payments.receipt', $payment) }}">{{ $payment->receipt_number }}</a></td><td>{{ $payment->payment_date->format('d M Y') }}</td><td>{{ $payment->payment_method }}</td><td>{{ $payment->recorder->name }}</td><td>{{ number_format($payment->amount_paid, 2) }}</td></tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted">No payments yet.</td></tr>
                @endforelse
                </tbody></table>
            </div>
        </div>
    </div>
</div>
@endsection
