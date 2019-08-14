<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFutureLettersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('future_letters', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email');
            $table->string('subject');
            $table->text('message');
            $table->timestamp('sending_date');
            $table->timestamp('email_verified_at')->nullable()->default(null);
            $table->timestamp('sent_at')->nullable()->default(null);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('future_letters');
    }
}
