<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Validator;


// UserControllerクラスの定義
class UserController extends Controller
{

    // 入力データのバリデーションルール
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'username' => ['required', 'string', 'max:30'],
            'email' => ['required', 'string', 'email', 'max:100'],
        ], [
            'username.required' => 'ユーザーネームは必須です。',
            'username.max' => 'ユーザーネームは30文字以内で入力してください。',
            'email.required' => 'メールアドレスは必須です。',
            'email.max' => 'メールアドレスは100文字以内で入力してください。',
        ]);
    }

    // 新規登録は RegisterController にて処理している
    // ログインは LoginController にて処理している
    public function delete($id) 
    {
        $user = User::findOrFail($id); // 指定されたIDのユーザーを取得

        if ($user) { // 質問が存在するか確認
            $user->delete(); // 問題を削除
            return redirect()->back()->with('success', 'User deleted successfully.'); // 削除後に前のページにリダイレクト
        } else {
            return redirect()->back()->with('error', 'User not found.'); // 問題が存在しない場合、エラーメッセージと共にリダイレクト
        }
    }

    public function edit($id)
    {
        $user = User::findOrFail($id); // 該当するIDのユーザーを取得
        return view('auth.passwords.user_edit', compact('user')); // ビューを返す
    }

    public function update(Request $request, $id)
    {
    // リクエストから全データを取得
    $data = $request->all();
    // バリデーション
    $this->validator($data)->validate();

    $user = User::findOrFail($id); // 該当するIDのユーザーを取得
    $user->fill($request->all()); // ユーザー情報を更新

    if (!$user->isDirty()) {
        return redirect()->back()->with('error', 'You need to specify different value to update.'); // 新しいデータが提供されていない場合のエラー
    }

    $user->save(); // DBに保存

    return redirect()->route('admin.dashboard.users')->with('success', 'User updated successfully.'); // 成功メッセージと共にリダイレクト
    }
}
