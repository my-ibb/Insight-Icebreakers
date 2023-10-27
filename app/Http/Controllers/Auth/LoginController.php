<?php

// 名前空間と依存関係を定義
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


// LoginControllerクラスの定義
class LoginController extends Controller
{
        // 入力データのバリデーションルール
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'email' => ['required', 'string', 'email', 'max:100'],
            'password' => ['required', 'string', 'min:8', 'max:255'],
        ], [
            'email.required' => 'メールアドレスは必須です。',
            'email.email' => '有効なメールアドレスを入力してください。',
            'email.max' => 'メールアドレスは100文字以内で入力してください。',
            'password.required' => 'パスワードは必須です。',
            'password.max' => 'パスワードは255文字以内で入力してください。',
            'password.min' => 'パスワードは最低8文字必要です。',
        ]);
    }

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

         // バリデーション
        $this->validator($credentials)->validate();
    
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
