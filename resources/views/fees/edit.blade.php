@extends('layouts.app')
@section('page-title', 'Edit Fee Structure')
@section('content')
<div class="panel"><div class="panel-header"><h2>Edit Fee Structure</h2></div><form method="POST" action="{{ route('fees.update', $fee) }}">@method('PUT') @include('fees.form')</form></div>
@endsection
