@extends('layouts.app')
@section('page-title', 'Create Fee Structure')
@section('content')
<div class="panel"><div class="panel-header"><h2>New Fee Structure</h2></div><form method="POST" action="{{ route('fees.store') }}">@include('fees.form')</form></div>
@endsection
