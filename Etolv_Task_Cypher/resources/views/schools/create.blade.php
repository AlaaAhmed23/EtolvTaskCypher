@extends('layouts.app')

@section('content')
<h1>Create School</h1>
<form action="{{ route('schools.store') }}" method="POST">
    @csrf
    <input type="text" name="name" placeholder="School Name" required>
    <button type="submit">Create</button>
</form>
@endsection