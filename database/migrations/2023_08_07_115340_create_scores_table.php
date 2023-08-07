<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scores', function (Blueprint $table) {
            $table->increments('id'); // 主キーであり、AUTO_INCREMENT
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('question_id');
            $table->unsignedTinyInteger('score');
            $table->unsignedInteger('question_count');
            $table->unsignedInteger('hint_count');
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
    
            // 外部キー制約の追加
            $table->foreign('user_id')
                ->references('id')->on('users');
            $table->foreign('question_id')
                ->references('id')->on('soup_game_questions');
        });
    }
        
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('scores');
    }
}
