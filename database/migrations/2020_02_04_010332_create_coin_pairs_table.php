<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoinPairsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coin_pairs', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('quote_coin_id');
            $table->unsignedBigInteger('base_coin_id');

            $table->foreign('quote_coin_id')->on('coins')->references('id');
            $table->foreign('base_coin_id')->on('coins')->references('id');

            $table->unique(['quote_coin_id', 'base_coin_id']);

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
        Schema::dropIfExists('coin_pairs');
    }
}
