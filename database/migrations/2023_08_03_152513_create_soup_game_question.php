<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSoupGameQuestionTable extends Migration
{
    public function up()
    {
        Schema::create('soup_game_question', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->string('content', 2000);
            $table->string('genre', 50);
            $table->string('difficulty', 50);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('soup_game_question');
    }
}
