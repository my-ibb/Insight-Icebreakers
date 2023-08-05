<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIntroGameQuestionsTable extends Migration
{
    public function up()
    {
        Schema::create('intro_game_questions', function (Blueprint $table) {
            $table->id();
            $table->string('content', 2000);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('intro_game_questions');
    }
}
