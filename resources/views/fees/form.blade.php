@csrf
<div class="row g-3">
    <div class="col-md-4">
        <label class="form-label">Class</label>
        <select name="class_id" class="form-select @error('class_id') is-invalid @enderror" required>@foreach($classes as $class)<option value="{{ $class->id }}" @selected(old('class_id', $fee->class_id ?? '') == $class->id)>{{ $class->class_name }}</option>@endforeach</select>
        @error('class_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label class="form-label">Amount</label>
        <input type="number" name="amount" value="{{ old('amount', $fee->amount ?? '') }}" min="0.01" step="0.01" class="form-control @error('amount') is-invalid @enderror" required>
        @error('amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label class="form-label">Academic Year</label>
        <input name="academic_year" value="{{ old('academic_year', $fee->academic_year ?? config('fees.current_academic_year')) }}" class="form-control @error('academic_year') is-invalid @enderror" required>
        @error('academic_year')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>
<div class="mt-4"><button class="btn btn-primary" type="submit"><i class="bi bi-check-circle"></i> Save Fee Structure</button><a href="{{ route('fees.index') }}" class="btn btn-light border">Cancel</a></div>
