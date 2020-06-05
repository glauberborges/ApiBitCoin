<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExtractTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('extract', function (Blueprint $table) {
            $table->id();
            $table->string('type',25);
            $table->decimal('amount_btc', 16, 8);
            $table->decimal('amount', 8, 2);
            $table->decimal('rate_sell', 16, 8);
            $table->longText('description');
            $table->timestamps();
            $table->unsignedBigInteger('user_id');
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
        Schema::dropIfExists('extract');
    }
}
