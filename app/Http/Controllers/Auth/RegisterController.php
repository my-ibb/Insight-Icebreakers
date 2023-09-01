<?php
// 名前空間と依存関係を定義
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// RegisterControllerクラスの定義
class RegisterController extends Controller
{
    // コンストラクタ：未ログインユーザーのみがこのコントローラを使えるようにする
    public function __construct()
    {
        $this->middleware('guest');
    }

    // 入力データのバリデーションルール
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'username.unique' => 'このユーザーネームは既に使用されています。',
            'email.unique' => 'このメールアドレスは既に使用されています。',
            'password.min' => 'パスワードは最低8文字必要です。',
        ]);
    }

    // 登録フォームを表示するためのメソッド
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    // ユーザー登録処理を行うメソッド
    public function register(Request $request)
    {
        // リクエストから全データを取得
        $data = $request->all();
        // バリデーション
        $this->validator($data)->validate();
        
        // ユーザー作成
        $user = $this->create($data);
    
        // ユーザーをログインさせて質問作成ページへリダイレクト
        Auth::login($user);
        return redirect()->to('/questions/create');
    }

    // ユーザーデータをDBに保存するためのメソッド
    protected function create(array $data)
    {
        return User::create([
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }
}
