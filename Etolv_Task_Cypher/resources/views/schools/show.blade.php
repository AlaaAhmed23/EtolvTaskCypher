@extends('layouts.app')

@section('content')
<h1>School Details</h1>
<p><strong>Name:</strong> {{ $school['name'] }}</p>
<a href="{{ route('schools.index') }}">Back</a>
@endsection