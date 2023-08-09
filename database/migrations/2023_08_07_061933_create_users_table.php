<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id'); // 主キーであり、AUTO_INCREMENT
            $table->string('username', 30)->unique(); // バイト数を50に設定
            $table->string('email', 100)->unique(); // バイト数を100に設定（例として）
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password', 15); // バイト数を255に設定（例として）
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
