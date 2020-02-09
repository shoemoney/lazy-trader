<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMarketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('markets', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('exchange_id');
            $table->unsignedBigInteger('coin_pair_id');

            $table->foreign('exchange_id')->references('id')->on('exchanges');
            $table->foreign('coin_pair_id')->references('id')->on('coin_pairs');

            $table->unique(['exchange_id', 'coin_pair_id']);

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
        Schema::dropIfExists('markets');
    }
}
