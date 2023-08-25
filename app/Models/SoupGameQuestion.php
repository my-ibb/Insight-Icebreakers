<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SoupGameQuestion extends Model
{
    // 他のコード ...

    public static function storeNewQuestion($data)
    {
        $newQuestion = new self();
        $newQuestion->question_content = $data['generated_question'];
        $newQuestion->answer_content = $data['generated_answer'];
        $newQuestion->genre = $data['genre'];
        $newQuestion->difficulty = $data['difficulty'];
        $newQuestion->user_id = auth()->user()->id;

        return $newQuestion->save();
    }
}
