<form action="{{ route('selfIntroductionLieGame.start') }}" method="post">
    @csrf
    <div>
        <label for="player_name">プレイヤー名:</label>
        <input type="text" id="player_name" name="player_name" required>
    </div>
    <div>
        <label for="number_of_players">参加人数:</label>
        <select id="number_of_players" name="number_of_players">
            @foreach($numberOfPlayersOptions as $option)
                <option value="{{ $option }}">{{ $option }}人</option>
            @endforeach
        </select>
    </div>
    <div>
        <label for="number_of_questions">設問個数:</label>
        <select id="number_of_questions" name="number_of_questions">
            @foreach($numberOfQuestionsOptions as $option)
                <option value="{{ $option }}">{{ $option }}個</option>
            @endforeach
        </select>
    </div>
    <button type="submit">開始</button>
</form>
