@extends('layouts.app')
@section('page-title', 'Reports')
@section('content')
<div class="panel mb-4">
    <div class="panel-header"><h2>Report Filters</h2><div class="d-flex gap-2"><a class="btn btn-outline-danger" href="{{ route('reports.pdf', request()->query()) }}"><i class="bi bi-file-earmark-pdf"></i> PDF</a><a class="btn btn-outline-success" href="{{ route('reports.excel', request()->query()) }}"><i class="bi bi-file-earmark-excel"></i> Excel</a></div></div>
    <form class="row g-3">
        <div class="col-md-3"><label class="form-label">From</label><input type="date" name="date_from" value="{{ request('date_from') }}" class="form-control @error('date_from') is-invalid @enderror">@error('date_from')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
        <div class="col-md-3"><label class="form-label">To</label><input type="date" name="date_to" value="{{ request('date_to') }}" class="form-control @error('date_to') is-invalid @enderror">@error('date_to')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
        <div class="col-md-3"><label class="form-label">Class</label><select name="class_id" class="form-select @error('class_id') is-invalid @enderror"><option value="">All</option>@foreach($classes as $class)<option value="{{ $class->id }}" @selected(request('class_id') == $class->id)>{{ $class->class_name }}</option>@endforeach</select>@error('class_id')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
        <div class="col-md-3"><label class="form-label">Student</label><select name="student_id" class="form-select @error('student_id') is-invalid @enderror"><option value="">All</option>@foreach($studentsList as $student)<option value="{{ $student->id }}" @selected(request('student_id') == $student->id)>{{ $student->full_name }}</option>@endforeach</select>@error('student_id')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
        <div class="col-12"><button class="btn btn-primary"><i class="bi bi-funnel"></i> Apply Filters</button><a href="{{ route('reports.index') }}" class="btn btn-light border">Reset</a></div>
    </form>
</div>
<div class="row g-3 mb-4"><div class="col-md-4"><div class="metric-card"><p>Total Expected Fees</p><h3>{{ number_format($totalExpected, 2) }}</h3></div></div><div class="col-md-4"><div class="metric-card"><p>Total Collected</p><h3>{{ number_format($totalCollected, 2) }}</h3></div></div><div class="col-md-4"><div class="metric-card"><p>Outstanding Balances</p><h3>{{ number_format($outstanding, 2) }}</h3></div></div></div>
<div class="panel mb-4"><div class="panel-header"><h2>Student Report</h2></div>@include('reports.student-table')</div>
<div class="panel"><div class="panel-header"><h2>Payment Report</h2></div>@include('reports.payment-table')</div>
@endsection
