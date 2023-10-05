{{-- 自己紹介表示画面 --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-center">自己紹介文表示</h1>
    @foreach($summaries as $summary)
        <h2>{{ $summary['player_name'] }}</h2>
        <p>{{ $summary['summary'] }}</p>
    @endforeach

    {{-- トップページに戻るボタンを追加 --}}
    <form action="{{ route('selfIntroductionLieGame.reset') }}" method="POST" class="mt-4">
        @csrf
        <button type="submit" class="btn btn-primary">トップページに戻る</button>
    </form>
</div>
@endsection
