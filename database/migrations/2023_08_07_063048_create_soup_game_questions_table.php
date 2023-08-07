<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('soup_game_questions', function (Blueprint $table) {
            $table->increments('id'); // 主キーであり、AUTO_INCREMENT
            $table->string('content', 2000)->nullable(false);
            $table->string('genre', 50)->nullable(false);
            $table->string('difficulty', 50)->nullable(false);
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')
                ->references('id')->on('users');
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
        Schema::dropIfExists('soup_game_questions');
    }
};