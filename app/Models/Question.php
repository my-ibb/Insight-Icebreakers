<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = ['content', 'answer', 'genre', 'difficulty', 'user_id'];
}
//このファイルもう使ってない！！！！