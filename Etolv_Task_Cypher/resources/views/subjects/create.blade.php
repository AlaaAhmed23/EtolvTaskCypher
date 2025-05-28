@extends('layouts.app')

@section('content')
<h1>Create Subject</h1>
<form action="{{ route('subjects.store') }}" method="POST">
    @csrf
    <input type="text" name="name" placeholder="Subject Name" required>
    <button type="submit">Create</button>
</form>
@endsection