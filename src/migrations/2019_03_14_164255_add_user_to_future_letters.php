<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUserToFutureLetters extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('future_letters', function (Blueprint $table) {
            $table->integer('user_id')->unsigned()->nullable()->after('id');
            $table->foreign('user_id')->references('id')->on('users');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('future_letters', function (Blueprint $table) {
            if (env('APP_ENV') != 'testing') {
                $table->dropForeign(['user_id']);
            }
            $table->dropColumn('user_id');
        });
    }
}
