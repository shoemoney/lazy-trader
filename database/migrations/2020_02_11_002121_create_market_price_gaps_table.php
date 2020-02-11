<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMarketPriceGapsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('market_price_gaps', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('market_id');

            $table->unsignedInteger('gap_timestamp_start');
            $table->unsignedInteger('gap_timestamp_end');

            $table->foreign('market_id')->references('id')->on('markets');

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
        Schema::dropIfExists('market_price_gaps');
    }
}
