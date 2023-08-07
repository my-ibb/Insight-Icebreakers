<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIntroGameQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('intro_game_questions', function (Blueprint $table) {
            $table->increments('id'); // 主キーであり、AUTO_INCREMENT
            $table->string('content', 2000)->nullable(false); // VARCHAR型で、バイト数は2000、NotNull制約
            $table->timestamps(); // created_atとupdated_atのDATETIME型
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('intro_game_questions');
    }
}
