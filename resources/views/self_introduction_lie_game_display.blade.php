{{-- 自己紹介表示画面 --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-center">自己紹介文表示</h1>
    @foreach($summaries as $summary)
        <h2>{{ $summary['player_name'] }}</h2>
        <p>{{ $summary['summary'] }}</p>
    @endforeach
</div>
@endsection
