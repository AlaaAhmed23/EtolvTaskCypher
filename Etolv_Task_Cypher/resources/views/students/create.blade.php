@extends('layouts.app')
@section('content')
<h1>Create Student</h1>

<form action="{{ route('students.store') }}" method="POST">
    @csrf

    <label>Name</label>
    <input type="text" name="name" required>

    <label>School</label>
    <select name="school_id" required>
        @foreach ($schools as $school)
        <option value="{{ $school['id'] }}">{{ $school['name'] }}</option>
        @endforeach
    </select>

    <label>Subjects</label>
    <select name="subject_ids[]" multiple>
        @foreach ($subjects as $subject)
        <option value="{{ $subject['id'] }}">{{ $subject['name'] }}</option>
        @endforeach
    </select>

    <button type="submit">Create</button>
</form>
@endsection