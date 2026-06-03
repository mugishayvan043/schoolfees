@extends('layouts.app')
@section('page-title', 'Edit User')
@section('content')
<div class="panel"><div class="panel-header"><h2>Edit User</h2></div><form method="POST" action="{{ route('users.update', $user) }}">@method('PUT') @include('users.form')</form></div>
@endsection
