@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-center">プレイヤー設定画面</h1>

    <form method="POST" action="{{ route('selfIntroductionLieGame.start') }}">
    @csrf

        <!-- プレイヤー名入力 -->
        <div class="form-group">
            <label for="player_name">プレイヤー名</label>
            <input type="text" class="form-control" id="player_name" name="player_name">
        </div>

        <!-- 参加人数選択 -->
        <div class="form-group">
            <label for="number_of_players">参加人数</label>
            <select class="form-control" id="number_of_players" name="number_of_players">
                @foreach ($numberOfPlayersOptions as $option)
                    <option value="{{ $option }}">{{ $option }}人</option>
                @endforeach
            </select>
        </div>

        <!-- 設問数選択 -->
        <div class="form-group">
            <label for="number_of_questions">設問数</label>
            <select class="form-control" id="number_of_questions" name="number_of_questions">
                @foreach ($numberOfQuestionsOptions as $option)
                    <option value="{{ $option }}">{{ $option }}問</option>
                @endforeach
            </select>
        </div>

        <!-- 送信ボタン -->
        <button type="submit" class="btn btn-primary">開始</button>
    </form>
</div>
@endsection
