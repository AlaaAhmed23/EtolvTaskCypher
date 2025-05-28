@extends('layouts.app')

@section('content')
<h1>Schools</h1>
<a href="{{ route('schools.create') }}">Create New School</a>
<ul>
    @foreach($schools as $school)
    <li>
        {{ $school['name'] }} |
        <a href="{{ route('schools.show', $school['id']) }}">View</a> |
        <a href="{{ route('schools.edit', $school['id']) }}">Edit</a>
        <form action="{{ route('schools.destroy', $school['id']) }}" method="POST" style="display:inline;">
            @csrf
            @method('DELETE')
            <button type="submit">Delete</button>
        </form>
    </li>
    @endforeach
</ul>
@endsection