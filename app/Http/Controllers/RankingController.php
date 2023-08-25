<?php

// 名前空間と依存関係を定義
namespace App\Http\Controllers;

use Illuminate\Http\Request;

// RankingControllerクラスの定義
class RankingController extends Controller
{
    // indexメソッド：ランキングのインデックスページを表示
    public function index()
    {
        // 'rankings.index'という名前のビューを返す
        return view('rankings.index');
    }
}

?>
