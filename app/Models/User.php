<?php

// 名前空間の定義
namespace App\Models;


// 必要なクラス・トレイトのインポート
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

// Userクラスの定義。Authenticatableクラスを継承。
class User extends Authenticatable
{
    use SoftDeletes;

    protected static function booted()
    {
        static::deleting(function ($user) {
            $user->soupGameQuestions()->delete();
            $user->scores()->delete();
        });
    }

    public function soupGameQuestions()
    {
        return $this->hasMany(SoupGameQuestion::class);
    }

    public function scores()
    {
        return $this->hasMany(Score::class);
    }
    // トレイトの使用
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * 一括代入可能な属性
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'name',
        'email',
        'password',
    ];

    /**
     * シリアライズから除外する属性
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * 型キャストする属性
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
