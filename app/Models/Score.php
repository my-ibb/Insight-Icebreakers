<?php
namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;
use Illuminate\Database\Eloquent\SoftDeletes;


class Score extends Model
{
    use SoftDeletes;
    protected $table = 'scores';
    protected $fillable = ['user_id', 'question_id', 'score', 'hint_count', 'question_count'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}