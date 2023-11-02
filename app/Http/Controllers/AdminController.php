<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Authファサードを使用
use App\Models\User;
use App\Models\SoupGameQuestion; 
use App\Models\IntroGameQuestion;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
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

    public function login()
    {
        if (Auth::check() && Auth::user()->role === 'admin') {
            return redirect()->route('admin.dashboard.users');
        } elseif (Auth::user()) {
            return redirect()->route('home');
        }
        return view('auth.passwords.admin.adminlogin'); 
    }
    
    public function authenticate(Request $request)
    {
        // リクエストからメールアドレスとパスワードを取得
        $credentials = $request->only('email', 'password');

        // バリデーション
        $this->validator($credentials)->validate();

        // 認証チェック
        if (Auth::attempt($credentials)) {
            // 認証に成功したらダッシュボードへリダイレクト
            return redirect()->route('admin.dashboard.users');
        }

        // 認証失敗：エラーメッセージとともに前のページへ戻る
        return back()->withErrors(['email' => '認証に失敗しました。']); 
    }

    public function dashboardUsers()
    {
        $users = User::paginate(5); // 5個ずつのページングを適用
        return view('auth.passwords.admin.dashboard_users', [
            'users' => $users,
        ]);
    }
        
    public function dashboardQuestions()
    {
        $questions = SoupGameQuestion::paginate(5); // 5個ずつのページングを適用
        return view('auth.passwords.admin.dashboard_questions', [
            'questions' => $questions,
        ]);
    }
    public function dashboardSelfIntroductionQuestions()
    {
        $introQuestions = IntroGameQuestion::paginate(5); // 10個ずつのページングを適用
        return view('auth.passwords.admin.dashboard_self_introduction_questions', [
            'introQuestions' => $introQuestions
        ]);
    }
}
