<?php

// 名前空間を定義
namespace App\Models;

// Eloquentモデルを使用
use Illuminate\Database\Eloquent\Model;

// SoupGameQuestionクラスの定義
class SoupGameQuestion extends Model
{
    // 他のコード（フィールド定義、リレーションなど）がここに来ます...

    // 新しい質問をデータベースに保存するための静的メソッド
    public static function storeNewQuestion($data)
    {
        //\Log::info('storeNewQuestion is called');
        // 新しいSoupGameQuestionオブジェクトを作成
        $newQuestion = new self();

        // 各プロパティにデータをセット
        $newQuestion->question_content = $data['generated_question'];
        $newQuestion->answer_content = $data['generated_answer'];
        $newQuestion->genre = $data['genre'];
        $newQuestion->difficulty = $data['difficulty'];
        
        // 現在認証されているユーザーのIDをセット
        $newQuestion->user_id = auth()->user()->id;

        // データベースに保存して、成功か失敗かを返す
        return $newQuestion->save();
    }
}
