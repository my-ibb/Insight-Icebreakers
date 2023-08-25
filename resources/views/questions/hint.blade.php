<!-- ベースとなるlayouts.appテンプレートを拡張 -->
@extends('layouts.app') 
<!-- ページタイトルをセット -->
@section('title', 'Hint Page')  

<!-- メインコンテンツの開始 -->
@section('content')  
<div class="container">
<!-- ページのタイトル -->
    <h1 class="text-center mb-4">Get a Hint</h1> 
    
    <!-- ヒントを取得するためのフォーム -->
    <form method="post" action="{{ route('generate-hint') }}">
        @csrf  <!-- CSRFトークンを追加 -->
        <div class="form-group">
            <label for="questionID">Question ID:</label>  <!-- Question IDの入力フィールド -->
            <input type="text" name="questionID" id="questionID" class="form-control">
        </div>
        <div class="text-center">
            <button type="submit" class="btn btn-primary">Get Hint</button>  <!-- ヒントを取得するボタン -->
        </div>
    </form>

    <!-- セッションにヒントが存在する場合、ヒントを表示 -->
    @if(session('hint'))
        <h2>Hint:</h2>
        <div class="alert alert-info">{{ session('hint') }}</div>
    @endif
</div>
<!-- メインコンテンツの終了 -->
@endsection  
