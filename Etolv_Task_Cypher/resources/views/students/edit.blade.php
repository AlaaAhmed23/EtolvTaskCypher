@extends('layouts.app')
@section('content')
<div class="container py-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h2 class="mb-0">Edit Student</h2>
        </div>

        <div class="card-body">
            <form action="{{ route('students.update', $student['id']) }}" method="POST" class="needs-validation"
                novalidate>
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="name" class="form-label">Student Name</label>
                    <input type="text" class="form-control" id="name" name="name"
                        value="{{ old('name', $student['name']) }}" required>
                    <div class="invalid-feedback">Please provide a valid name.</div>
                </div>

                <div class="mb-3">
                    <label for="school_id" class="form-label">School</label>
                    <select class="form-select" id="school_id" name="school_id" required>
                        @foreach($schools as $school)
                        <option value="{{ $school['id'] }}"
                            {{ ($student['school']['id'] ?? null) == $school['id'] ? 'selected' : '' }}>
                            {{ $school['name'] }}
                        </option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback">Please select a school.</div>
                </div>

                <div class="mb-4">
                    <label for="subject_ids" class="form-label">Subjects</label>
                    <select class="form-select" id="subject_ids" name="subject_ids[]" multiple size="5">
                        @foreach($subjects as $subject)
                        <option value="{{ $subject['id'] }}"
                            {{ in_array($subject['id'], $subjects) ? 'selected' : '' }}>
                            {{ $subject['name'] }}
                        </option>
                        @endforeach
                    </select>
                    <small class="text-muted">Hold Ctrl (Windows) or Command (Mac) to select multiple</small>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('students.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-1"></i> Update Student
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.card {
    border-radius: 0.5rem;
}

.card-header {
    padding: 1rem 1.5rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.2);
}

.form-select[multiple] {
    min-height: 150px;
    padding: 0.5rem;
}

.form-label {
    font-weight: 500;
    margin-bottom: 0.5rem;
}
</style>

<script>
// Bootstrap form validation
(function() {
    'use strict'
    const forms = document.querySelectorAll('.needs-validation')
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
            }
            form.classList.add('was-validated')
        }, false)
    })
})()
</script>
@endsection