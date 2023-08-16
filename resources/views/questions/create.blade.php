<!-- resources/views/questions/create.blade.php -->
<h1>Create a Question</h1>

<form method="post" action="{{ route('generate-question') }}">
    @csrf

    <div>
        <label for="genre">Genre:</label>
        <select name="genre" id="genre">
            <option value="history">History</option>
            <option value="science">Science</option>
            <option value="math">Math</option>
            <option value="literature">Literature</option>
            <!-- 他のジャンルを追加してもよい -->
        </select>
    </div>

    <div>
        <label for="difficulty">Difficulty:</label>
        <select name="difficulty" id="difficulty">
            <option value="easy">Easy</option>
            <option value="medium">Medium</option>
            <option value="hard">Hard</option>
        </select>
    </div>

    <div>
        <button type="submit">Generate Question</button>
    </div>

</form>

<!-- 生成された質問を表示するための領域 -->
@if(session('question'))
    <h2>Generated Question:</h2>
    <p>{{ session('question') }}</p>
@endif
