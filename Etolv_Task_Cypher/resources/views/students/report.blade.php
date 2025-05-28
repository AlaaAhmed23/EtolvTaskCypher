@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">📋 Student Subject & School Report</h1>

    <form method="GET" class="row g-3 mb-4">
        <div class="col-md-4">
            <label for="school" class="form-label">Filter by School</label>
            <select name="school" id="school" class="form-select">
                <option value="">All Schools</option>
                @foreach ($allSchools as $s)
                <option value="{{ $s['name'] }}" {{ request('school') == $s['name'] ? 'selected' : '' }}>
                    {{ $s['name'] }}
                </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4">
            <label for="subject" class="form-label">Filter by Subject</label>
            <select name="subject" id="subject" class="form-select">
                <option value="">All Subjects</option>
                @foreach ($allSubjects as $sub)
                <option value="{{ $sub['name'] }}" {{ request('subject') == $sub['name'] ? 'selected' : '' }}>
                    {{ $sub['name'] }}
                </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4 d-flex align-items-end">
            <button type="submit" class="btn btn-primary me-2">Filter</button>
            <a href="{{ route('students.report') }}" class="btn btn-secondary">Reset</a>
            <a href="{{ route('students.report', array_merge(request()->all(), ['export' => 'csv'])) }}"
                class="btn btn-success"> Export CSV </a>
        </div>
    </form>

    <table class="table table-bordered table-hover">
        <thead class="table-light">
            <tr>
                <th>Student Name</th>
                <th>School</th>
                <th>Subjects</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($students as $student)
            <tr>
                <td>{{ $student['name'] }}</td>
                <td>{{ $student['school']['name'] ?? 'N/A' }}</td>
                <td>
                    @forelse ($student['subjects'] as $subject)
                    <span class="badge bg-primary">{{ $subject['name'] }}</span>
                    @empty
                    <span class="text-muted">No subjects</span>
                    @endforelse
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="3" class="text-center">No student data available.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection