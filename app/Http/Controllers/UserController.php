<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;


// UserControllerクラスの定義
class UserController extends Controller
{
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
    // バリデーションルールを定義
    $request->validate([
        'username' => 'required|string|max:255',
        'email' => 'required|email|max:255|unique:users,email,' . $id,
        // 必要に応じて他のフィールドに対するバリデーションルールを追加
    ]);

    $user = User::findOrFail($id); // 該当するIDのユーザーを取得
    $user->fill($request->all()); // ユーザー情報を更新
    $user->save(); // DBに保存

    return redirect()->route('admin.dashboard.users'); // ユーザー一覧ページにリダイレクト
    }

    public function editUser($id)
{
    try {
        $user = User::findOrFail($id);
    } catch (ModelNotFoundException $e) {
        return redirect()->back()->with('error', 'User not found.');
    }

    return view('auth.passwords.user_edit', compact('user'));
}

}
