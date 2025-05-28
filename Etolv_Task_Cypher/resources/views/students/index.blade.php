@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Students Management</h1>

    <div class="mb-4">
        <a href="{{ route('students.create') }}" class="btn btn-primary">Create New Student</a>
        <a href="{{ route('students.paginated') }}" class="btn btn-info">Test Paginated</a>
        <a href="{{ route('students.report') }}" class="btn btn-success">Students Report</a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th>#</th>
                            <th>Student Name</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $index => $student)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $student['name'] }}</td>
                            <td>
                                <a href="{{ route('students.show', $student['id']) }}"
                                    class="btn btn-sm btn-info">View</a>
                                <a href="{{ route('students.edit', $student['id']) }}"
                                    class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('students.destroy', $student['id']) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger"
                                        onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-3">
        Showing {{ count($students) }} of {{ count($students) }} students
    </div>
</div>
@endsection