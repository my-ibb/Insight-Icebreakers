{{-- 自己紹介表示画面 --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-center">自己紹介文表示</h1>
    @foreach($summaries as $index => $summary)
        <h2>{{ $summary['player_name'] }}</h2>
        <div style="display:none;" id="answerArea-{{ $index }}">
            <p>{{ $summary['summary'] }}</p>
        </div>
        <button onclick="showAnswer({{ $index }})">表示/非表示</button>
    @endforeach

    {{-- トップページに戻るボタンを追加 --}}
    <form action="{{ route('selfIntroductionLieGame.reset') }}" method="POST" class="mt-4">
        @csrf
        <button type="submit" class="btn btn-primary">トップページに戻る</button>
    </form>
</div>

<script>
    function showAnswer(id) {
        var answerArea = document.getElementById('answerArea-' + id);
        if (answerArea.style.display === 'block') {
            answerArea.style.display = 'none';
        } else {
            answerArea.style.display = 'block';
        }
    }
</script>
@endsection
