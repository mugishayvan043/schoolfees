@extends('layouts.app')
@section('page-title', 'Receipt')
@section('content')
@php($student = $payment->student)
@php($totalFee = $student->totalFee())
@php($paid = $student->payments()->sum('amount_paid'))
<div class="receipt-toolbar mb-3"><button class="btn btn-primary" onclick="window.print()"><i class="bi bi-printer"></i> Print Receipt</button><a href="{{ route('payments.index') }}" class="btn btn-light border">Back</a></div>
<div class="receipt">
    <div class="receipt-head">
        <div><h2>School Fee Receipt</h2><p>Official payment receipt</p></div>
        <div class="receipt-number">{{ $payment->receipt_number }}</div>
    </div>
    <div class="row g-4">
        <div class="col-md-6"><h3>Student</h3><p><strong>{{ $student->full_name }}</strong><br>{{ $student->student_code }}<br>{{ $student->schoolClass->class_name }}</p></div>
        <div class="col-md-6"><h3>Payment</h3><p>Date: {{ $payment->payment_date->format('d M Y') }}<br>Method: {{ $payment->payment_method }}<br>Recorded by: {{ $payment->recorder->name }}</p></div>
    </div>
    <table class="table receipt-table"><tbody><tr><th>Total Fee</th><td>{{ number_format($totalFee, 2) }}</td></tr><tr><th>Amount Paid on Receipt</th><td>{{ number_format($payment->amount_paid, 2) }}</td></tr><tr><th>Total Paid</th><td>{{ number_format($paid, 2) }}</td></tr><tr><th>Remaining Balance</th><td>{{ number_format(max($totalFee - $paid, 0), 2) }}</td></tr></tbody></table>
    <div class="receipt-foot"><span>Finance Officer Signature</span><span>School Stamp</span></div>
</div>
@endsection
