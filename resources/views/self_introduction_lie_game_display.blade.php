{{-- 自己紹介表示画面 --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <br>
    <h1 class="text-center">自己紹介文表示</h1>
    @foreach($summaries as $index => $summary)
        <div class="my-4">
            <h2 class="text-center">{{ $summary['player_name'] }}の自己紹介文</h2>
            <br>
            <div style="display:none;" id="answerArea-{{ $index }}">
                <p>{{ $summary['summary'] }}</p>
            </div>
            <div class="text-center"> {{-- text-center classを追加してボタンを中央に配置 --}}
                <button class="btn btn-primary" onclick="showAnswer({{ $index }})">表示/非表示</button> {{-- btn btn-primary classを追加してボタンデザインを統一 --}}
            </div>
        </div>
    @endforeach

    {{-- トップページに戻るボタンを追加 --}}
    <form action="{{ route('selfIntroductionLieGame.reset') }}" method="POST" class="mt-4">
        @csrf
        <div class="text-center"> {{-- ここでもtext-center classを使用 --}}
            <br>
            <button type="submit" class="btn btn-primary">トップページに戻る</button>
        </div>
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
