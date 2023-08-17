<h1>{{ $question['title'] }}</h1>
<p>{{ $question['content'] }}</p>

<h2>Input your question:</h2>
<form action="{{ route('questions.storeQuestion', $question['id']) }}" method="post">
    @csrf
    <textarea name="question_content"></textarea>
    <button type="submit">Submit</button>
</form>
