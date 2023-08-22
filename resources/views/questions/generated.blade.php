@extends('layouts.app')

@section('title', 'Generated Question Page')

@section('content')

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generated Question</title>
</head>
<body>
    <h2>Generated Question:</h2>
    <p>{{ $question }}</p>
    <a href="{{ route('questions.create') }}">Back to Create Question</a>
</body>
</html>
@endsection
