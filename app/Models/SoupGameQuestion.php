<?php

namespace App\Models;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;

class SoupGameQuestion extends Model
{
    // ユーザーへのリレーション
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    // $fillable プロパティを追加
    protected $fillable = ['question_content', 'answer_content', 'genre', 'difficulty', 'user_id'];
    
    public static function storeNewQuestion($data)
    {        
        // 新しい SoupGameQuestion オブジェクトを作成
        $newQuestion = new self();
        
        // 各プロパティにデータをセット
        $newQuestion->question_content = $data['generated_question'];
        $newQuestion->answer_content = $data['generated_answer'];
        $newQuestion->genre = $data['genre'];
        $newQuestion->difficulty = $data['difficulty'];
        // 現在認証されているユーザーのIDをセット
        if (auth()->check()) {  // ユーザーがログインしているか確認
            $newQuestion->user_id = auth()->user()->id;
        } else {
            return false;
        }
        
        try {
            // データベースに保存
            $result = $newQuestion->save();
                        
            return $result;
        } catch (\Exception $e) {
            return false;
        }
    }
    public function fetchAndStoreQuestion() {
        $response = Http::get("https://api.openai.com/v1/chat/completions");

        if($response->failed()) {
            return false; // または何らかのエラー処理
        }
        $data = $response->json();
            
        // $dataの中身をデバッグ（オプション）
        //dd($data);
    
        // この$dataを用いて問題をデータベースに保存
        SoupGameQuestion::storeNewQuestion($data);
    }
}
