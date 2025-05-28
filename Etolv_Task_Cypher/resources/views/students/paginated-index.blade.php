@extends('layouts.app')
@section('content')
<div class="container">
    <h1>Students (Paginated)</h1>

    <form method="GET" action="{{ route('students.paginated') }}">
        <div class="input-group mb-3">
            <input type="text" name="search" class="form-control" placeholder="Search students..."
                value="{{ request('search') }}">
            <button class="btn btn-outline-secondary" type="submit">Search</button>
        </div>
    </form>

    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>School</th>
                <th>Subjects</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($students as $student)
            <tr>
                <td>{{ $student['name'] }}</td>
                <td>{{ $student['school']['name'] ?? 'N/A' }}</td>
                <td>
                    @foreach($student['subjects'] as $subject)
                    <span class="badge bg-primary">{{ $subject['name'] }}</span>
                    @endforeach
                </td>
                <td>
                    <a href="{{ route('students.show', $student['id']) }}" class="btn btn-sm btn-info">View</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center">
            @php
            $totalPages = ceil($pagination['total'] / $pagination['per_page']);
            $currentPage = $pagination['current_page'];
            @endphp

            <!-- Previous-->
            @if($currentPage > 1)
            <li class="page-item">
                <a class="page-link" href="{{ request()->fullUrlWithQuery(['page' => $currentPage - 1]) }}"
                    aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>
            @endif

            <!--Page Numbers-->
            @for($i=1; $i <=$totalPages; $i++) <li class="page-item {{ $i == $currentPage ? 'active' : '' }}">
                <a class="page-link" href="{{ request()->fullUrlWithQuery(['page' => $i]) }}">{{ $i }}</a>
                </li>
                @endfor

                <!-- Next-->
                @if($currentPage < $totalPages) <li class="page-item">
                    <a class="page-link" href="{{ request()->fullUrlWithQuery(['page' => $currentPage + 1]) }}"
                        aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                    </li>
                    @endif
        </ul>
    </nav>
</div>
@endsection