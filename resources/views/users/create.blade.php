@extends('layouts.app')
@section('page-title', 'Create User')
@section('content')
<div class="panel"><div class="panel-header"><h2>New User</h2></div><form method="POST" action="{{ route('users.store') }}">@include('users.form')</form></div>
@endsection
