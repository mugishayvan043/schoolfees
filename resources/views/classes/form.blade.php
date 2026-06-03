@csrf
<div class="mb-3">
    <label class="form-label">Class Name</label>
    <input name="class_name" value="{{ old('class_name', $class->class_name ?? '') }}" class="form-control @error('class_name') is-invalid @enderror" required>
    @error('class_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>
<div class="mb-3">
    <label class="form-label">Description</label>
    <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="4">{{ old('description', $class->description ?? '') }}</textarea>
    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>
<button class="btn btn-primary" type="submit"><i class="bi bi-check-circle"></i> Save Class</button>
<a href="{{ route('classes.index') }}" class="btn btn-light border">Cancel</a>
