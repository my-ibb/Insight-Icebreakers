<?php

// 名前空間の定義。これによりControllerクラスがApp\Http\Controllers内に存在することを示す。
namespace App\Http\Controllers;

// 必要なトレイトとクラスをインポートします。
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

// Controllerクラスの定義。これは基本的なコントローラーで、すべての他のコントローラーはこのクラスを継承することが多い。
class Controller extends BaseController
{
    // 以下のトレイトを使用。これにより、このクラスを継承するすべてのコントローラーでこれらの機能を簡単に使用できます。
    
    // AuthorizesRequests: 認可（誰が何を許可されているか）に関連するヘルパーメソッドを提供します。
    use AuthorizesRequests;

    // DispatchesJobs: ジョブ（一般的には非同期タスク）をディスパッチ（送出）するためのヘルパーメソッドを提供します。
    use DispatchesJobs;

    // ValidatesRequests: リクエストデータのバリデーション（検証）を簡単に行うためのヘルパーメソッドを提供します。
    use ValidatesRequests;
}
