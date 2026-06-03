@extends('layouts.app')
@section('page-title', 'Payments')
@section('content')
<div class="panel">
    <div class="panel-header"><h2>Payment Management</h2><a href="{{ route('payments.create') }}" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Record Payment</a></div>
    <form class="row g-2 mb-3"><div class="col-md-6"><input name="search" value="{{ request('search') }}" class="form-control" placeholder="Search receipt or student"></div><div class="col"><button class="btn btn-outline-primary">Search</button></div></form>
    <div class="table-responsive"><table class="table align-middle"><thead><tr><th>Receipt</th><th>Student</th><th>Class</th><th>Date</th><th>Method</th><th>Amount</th><th></th></tr></thead><tbody>
    @forelse($payments as $payment)
        <tr><td><strong>{{ $payment->receipt_number }}</strong></td><td>{{ $payment->student->full_name }}</td><td>{{ $payment->student->schoolClass->class_name }}</td><td>{{ $payment->payment_date->format('d M Y') }}</td><td>{{ $payment->payment_method }}</td><td>{{ number_format($payment->amount_paid, 2) }}</td><td class="text-end table-actions"><a class="btn btn-sm btn-outline-primary" href="{{ route('payments.receipt', $payment) }}"><i class="bi bi-printer"></i></a><a class="btn btn-sm btn-outline-secondary" href="{{ route('payments.edit', $payment) }}"><i class="bi bi-pencil"></i></a><form method="POST" action="{{ route('payments.destroy', $payment) }}" onsubmit="return confirm('Delete this payment?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button></form></td></tr>
    @empty
        <tr><td colspan="7" class="text-center text-muted">No payments found.</td></tr>
    @endforelse
    </tbody></table></div>{{ $payments->links() }}
</div>
@endsection
