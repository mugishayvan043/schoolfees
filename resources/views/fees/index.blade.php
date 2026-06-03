@extends('layouts.app')
@section('page-title', 'Fee Structure')
@section('content')
<div class="panel">
    <div class="panel-header"><h2>Fee Structure Management</h2><a href="{{ route('fees.create') }}" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Add Fee</a></div>
    <form class="row g-2 mb-3"><div class="col-md-4"><input name="academic_year" value="{{ request('academic_year') }}" class="form-control" placeholder="Academic year"></div><div class="col"><button class="btn btn-outline-primary">Filter</button></div></form>
    <div class="table-responsive"><table class="table align-middle"><thead><tr><th>Class</th><th>Academic Year</th><th>Amount</th><th></th></tr></thead><tbody>
    @forelse($fees as $fee)
        <tr><td>{{ $fee->schoolClass->class_name }}</td><td>{{ $fee->academic_year }}</td><td>{{ number_format($fee->amount, 2) }}</td><td class="text-end table-actions"><a class="btn btn-sm btn-outline-secondary" href="{{ route('fees.edit', $fee) }}"><i class="bi bi-pencil"></i></a><form method="POST" action="{{ route('fees.destroy', $fee) }}" onsubmit="return confirm('Delete this fee structure?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button></form></td></tr>
    @empty
        <tr><td colspan="4" class="text-center text-muted">No fee structures found.</td></tr>
    @endforelse
    </tbody></table></div>{{ $fees->links() }}
</div>
@endsection
