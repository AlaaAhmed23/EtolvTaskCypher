@extends('layouts.app')

@section('content')
<h1>Edit Subject</h1>
<form action="{{ route('subjects.update', $subject['id']) }}" method="POST">
    @csrf
    @method('PUT')
    <input type="text" name="name" value="{{ $subject['name'] }}" required>
    <button type="submit">Update</button>
</form>
@endsection