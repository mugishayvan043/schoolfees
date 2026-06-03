@extends('layouts.app')
@section('page-title', 'Classes')
@section('content')
<div class="panel">
    <div class="panel-header"><h2>Class Management</h2><a href="{{ route('classes.create') }}" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Add Class</a></div>
    <form class="row g-2 mb-3"><div class="col-md-6"><input name="search" value="{{ request('search') }}" class="form-control" placeholder="Search classes"></div><div class="col"><button class="btn btn-outline-primary">Search</button></div></form>
    <div class="table-responsive">
        <table class="table align-middle"><thead><tr><th>Class</th><th>Description</th><th>Students</th><th></th></tr></thead><tbody>
        @forelse($classes as $class)
            <tr><td><strong>{{ $class->class_name }}</strong></td><td>{{ $class->description ?: '-' }}</td><td>{{ $class->students_count }}</td><td class="text-end table-actions"><a class="btn btn-sm btn-outline-primary" href="{{ route('classes.show', $class) }}"><i class="bi bi-eye"></i></a><a class="btn btn-sm btn-outline-secondary" href="{{ route('classes.edit', $class) }}"><i class="bi bi-pencil"></i></a><form method="POST" action="{{ route('classes.destroy', $class) }}" onsubmit="return confirm('Delete this class and related records?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button></form></td></tr>
        @empty
            <tr><td colspan="4" class="text-center text-muted">No classes found.</td></tr>
        @endforelse
        </tbody></table>
    </div>
    {{ $classes->links() }}
</div>
@endsection
