<?php
namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;

class Score extends Model
{
    protected $table = 'scores';
    protected $fillable = ['user_id', 'question_id', 'score', 'hint_count', 'question_count'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}