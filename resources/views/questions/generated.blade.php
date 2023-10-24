<!-- layouts.appというBladeテンプレートを拡張しています -->
@extends('layouts.app')

<!-- ページのタイトルを設定 -->
@section('title', 'Generated Question Page')

<!-- contentセクションを開始 -->
@section('content')

<!-- コンテナを設定して中央に配置 -->
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 center-div">
            <!-- カードレイアウトを作成 -->
            <div class="card">
                <!-- カードのヘッダーに質問のタイトルを表示 -->
                <div class="card-header bg-light text-dark">
                    <h2>Generated Question:</h2>
                </div>
                <!-- カードの本文に生成された質問を表示 -->
                <div class="card-body">
                    <p>{{ $question }}</p>
                    
                    <!-- 質問作成ページへ戻るボタン -->
                    <a href="{{ route('questions.create') }}" class="btn btn-primary">Back to Create Question</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- contentセクションの終了 -->
@endsection
{{-- つかってない --}}