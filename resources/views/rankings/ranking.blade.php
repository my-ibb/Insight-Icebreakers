@extends('layouts.app')

@section('content')
  <h1>ランキング</h1>
  <table class="table">
    <thead>
      <tr>
        <th scope="col">順位</th>
        <th scope="col">ユーザーID</th>
        <th scope="col">総得点</th>
        <th scope="col">質問回数</th>
        <th scope="col">ヒント回数</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($rankings as $index => $ranking)
        <tr>
          <th scope="row">{{ $index + 1 }}</th>
          <td>{{ $ranking->user_id }}</td>
          <td>{{ $ranking->total_score }}</td>
          <td>{{ $ranking->total_questions }}</td>
          <td>{{ $ranking->total_hints }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
@endsection
