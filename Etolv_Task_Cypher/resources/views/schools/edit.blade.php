@extends('layouts.app')

@section('content')
<h1>Edit School</h1>
<form action="{{ route('schools.update', $school['id']) }}" method="POST">
    @csrf
    @method('PUT')
    <input type="text" name="name" value="{{ $school['name'] }}" required>
    <button type="submit">Update</button>
</form>
@endsection