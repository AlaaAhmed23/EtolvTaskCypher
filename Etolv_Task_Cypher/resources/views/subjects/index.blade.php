@extends('layouts.app')

@section('content')
<h1>Subjects</h1>
<a href="{{ route('subjects.create') }}">Create New Subject</a>
<ul>
    @foreach($subjects as $subject)
    <li>
        {{ $subject['name'] }} |
        <a href="{{ route('subjects.show', $subject['id']) }}">View</a> |
        <a href="{{ route('subjects.edit', $subject['id']) }}">Edit</a>
        <form action="{{ route('subjects.destroy', $subject['id']) }}" method="POST" style="display:inline;">
            @csrf
            @method('DELETE')
            <button type="submit">Delete</button>
        </form>
    </li>
    @endforeach
</ul>
@endsection