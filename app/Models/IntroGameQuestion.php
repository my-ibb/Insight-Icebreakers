<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IntroGameQuestion extends Model
{
    use HasFactory;

    // モデルが使用するテーブル名を定義
    protected $table = 'intro_game_questions'; // ここにテーブル名を設定

    // 代入を許可するカラムを指定
    protected $fillable = ['content']; // 例: contentカラムを代入許可。必要に応じて変更してください。
}
