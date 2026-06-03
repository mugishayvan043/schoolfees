@csrf
<div class="row g-3">
    <div class="col-md-4"><label class="form-label">Name</label><input name="name" value="{{ old('name', $user->name ?? '') }}" class="form-control @error('name') is-invalid @enderror" required>@error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
    <div class="col-md-4"><label class="form-label">Email</label><input type="email" name="email" value="{{ old('email', $user->email ?? '') }}" class="form-control @error('email') is-invalid @enderror" required>@error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
    <div class="col-md-4"><label class="form-label">Role</label><select name="role" class="form-select @error('role') is-invalid @enderror" required><option value="admin" @selected(old('role', $user->role ?? '') === 'admin')>Administrator</option><option value="accountant" @selected(old('role', $user->role ?? 'accountant') === 'accountant')>Accountant</option></select>@error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
    <div class="col-md-6"><label class="form-label">Password</label><input type="password" name="password" minlength="5" class="form-control @error('password') is-invalid @enderror" @if(!isset($user)) required @endif>@error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
    <div class="col-md-6"><label class="form-label">Confirm Password</label><input type="password" name="password_confirmation" minlength="5" class="form-control" @if(!isset($user)) required @endif></div>
</div>
<div class="mt-4"><button class="btn btn-primary">Save User</button><a href="{{ route('users.index') }}" class="btn btn-light border">Cancel</a></div>
