@extends('layouts.app')
@section('page-title', 'Users')
@section('content')
<div class="panel">
    <div class="panel-header"><h2>User Management</h2><a href="{{ route('users.create') }}" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Add User</a></div>
    <table class="table align-middle"><thead><tr><th>Name</th><th>Email</th><th>Role</th><th>Created</th><th></th></tr></thead><tbody>
    @foreach($users as $user)<tr><td>{{ $user->name }}</td><td>{{ $user->email }}</td><td><span class="badge text-bg-primary">{{ ucfirst($user->role) }}</span></td><td>{{ $user->created_at->format('d M Y') }}</td><td class="text-end table-actions"><a class="btn btn-sm btn-outline-secondary" href="{{ route('users.edit', $user) }}"><i class="bi bi-pencil"></i></a><form method="POST" action="{{ route('users.destroy', $user) }}" onsubmit="return confirm('Delete this user?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button></form></td></tr>@endforeach
    </tbody></table>{{ $users->links() }}
</div>
@endsection
