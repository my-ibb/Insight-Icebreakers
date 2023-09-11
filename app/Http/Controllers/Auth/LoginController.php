<?php

// 名前空間と依存関係を定義
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// LoginControllerクラスの定義
class LoginController extends Controller
{
    // ログインフォームを表示するためのメソッド
    public function showLoginForm()
    {
        return view('auth.login'); // ログインフォームのビューを返す
    }

    // ログイン処理を行うメソッド
    public function login(Request $request)
    {
        // リクエストからメールアドレスとパスワードを取得
        $credentials = $request->only('email', 'password');
    
        // 認証試行
        if (Auth::attempt($credentials)) {
            // セッションから選んだ問題のIDを取得
            $selectedQuestionId = session('selected_question_id', null);

            // もしセッションに問題IDが保存されていれば、その問題の詳細ページにリダイレクト
            if ($selectedQuestionId !== null) {
                // セッションから選んだ問題のIDを削除
                $request->session()->forget('selected_question_id');
                // 該当の問題の詳細ページにリダイレクト
                return redirect()->route('questions.detail', ['id' => $selectedQuestionId]);
            }

            // 通常は質問作成ページへリダイレクト
            return redirect()->to('/questions/create');
        }
    
        // 認証失敗：エラーメッセージとともに前のページへ戻る
        return back()->withErrors(['email' => '認証に失敗しました。']);
    }
    
    // ログアウト処理を行うメソッド
    public function logout()
    {
        // ログアウト
        Auth::logout();
        
        // トップページへリダイレクト
        return redirect('/');
    }
}
