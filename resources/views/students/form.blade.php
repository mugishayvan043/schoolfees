@csrf
<div class="row g-3">
    <div class="col-md-4">
        <label class="form-label">Student Code</label>
        <input name="student_code" value="{{ old('student_code', $student->student_code ?? '') }}" class="form-control @error('student_code') is-invalid @enderror" required>
        @error('student_code')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label class="form-label">First Name</label>
        <input name="first_name" value="{{ old('first_name', $student->first_name ?? '') }}" class="form-control @error('first_name') is-invalid @enderror" required>
        @error('first_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label class="form-label">Last Name</label>
        <input name="last_name" value="{{ old('last_name', $student->last_name ?? '') }}" class="form-control @error('last_name') is-invalid @enderror" required>
        @error('last_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label class="form-label">Gender</label>
        <select name="gender" class="form-select @error('gender') is-invalid @enderror" required>
            @foreach(['Male', 'Female', 'Other'] as $gender)
                <option value="{{ $gender }}" @selected(old('gender', $student->gender ?? '') === $gender)>{{ $gender }}</option>
            @endforeach
        </select>
        @error('gender')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label class="form-label">Class</label>
        <select name="class_id" class="form-select @error('class_id') is-invalid @enderror" required>
            @foreach($classes as $class)
                <option value="{{ $class->id }}" @selected(old('class_id', $student->class_id ?? '') == $class->id)>{{ $class->class_name }}</option>
            @endforeach
        </select>
        @error('class_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label class="form-label">Phone</label>
        <input name="phone" value="{{ old('phone', $student->phone ?? '') }}" class="form-control @error('phone') is-invalid @enderror">
        @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-12">
        <label class="form-label">Address</label>
        <textarea name="address" class="form-control @error('address') is-invalid @enderror" rows="3">{{ old('address', $student->address ?? '') }}</textarea>
        @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>
<div class="mt-4 d-flex gap-2">
    <button class="btn btn-primary" type="submit"><i class="bi bi-check-circle"></i> Save Student</button>
    <a href="{{ route('students.index') }}" class="btn btn-light border">Cancel</a>
</div>
