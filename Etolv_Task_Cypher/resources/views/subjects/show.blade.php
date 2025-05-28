@extends('layouts.app')

@section('content')
<h1>Subject Details</h1>
<p><strong>Name:</strong> {{ $subject['name'] }}</p>
<a href="{{ route('subjects.index') }}">Back</a>
@endsection