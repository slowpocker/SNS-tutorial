<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFollowersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('followers', function (Blueprint $table) {
            //自分自身はfollowing_id,フォローしているユーザはfollowed_idとする
            $table->foreignId('following_id')->constrained('users')->comment('フォローしているユーザのID');
            $table->foreignId('followed_id')->constrained('users')->comment('フォローされているユーザのID');
            $table->index('following_id');
            $table->index('followed_id');

            //以下の組み合わせを一意にして同じIDの登録を防ぐ
            $table->unique([
                'following_id',
                'followed_id'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('followers');
    }
}
