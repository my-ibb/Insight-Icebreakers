<!-- resources/views/questions/index.blade.php -->

<h1>Question List</h1>

<ul>
    @foreach ($questions as $question)
    <li>
        <a href="{{ route('questions.detail', ['id' => $question['id']]) }}">{{ $question['title'] }}</a>
    </li>
    @endforeach
</ul>
<a href="{{ route('questions.create') }}">Question Create</a>
