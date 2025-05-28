@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h2 class="mb-0">Student Details</h2>
        </div>

        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="detail-item">
                        <h5 class="detail-label">Name:</h5>
                        <p class="detail-value">{{ $student['name'] }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="detail-item">
                        <h5 class="detail-label">School:</h5>
                        <p class="detail-value">{{ $student['school']['name'] ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <div class="subject-section">
                <h4 class="section-title">Subjects</h4>
                @if (!empty($student['subjects']))
                <div class="subject-list">
                    @foreach ($student['subjects'] as $subject)
                    <div class="subject-badge">
                        {{ $subject['name'] }}
                    </div>
                    @endforeach
                </div>
                @else
                <div class="alert alert-info">No subjects enrolled.</div>
                @endif
            </div>
        </div>

        <div class="card-footer bg-light">
            <a href="{{ route('students.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left"></i> Back to Students
            </a>
        </div>
    </div>
</div>

<style>
.detail-item {
    margin-bottom: 1.5rem;
}

.detail-label {
    color: #6c757d;
    font-size: 0.9rem;
    margin-bottom: 0.25rem;
}

.detail-value {
    font-size: 1.1rem;
    font-weight: 500;
}

.section-title {
    color: #495057;
    border-bottom: 1px solid #dee2e6;
    padding-bottom: 0.5rem;
    margin-bottom: 1.5rem;
}

.subject-list {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.subject-badge {
    background-color: #e9ecef;
    padding: 0.5rem 1rem;
    border-radius: 50rem;
    font-size: 0.9rem;
}
</style>
@endsection