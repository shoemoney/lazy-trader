<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMarketPricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('market_prices', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('market_id');
            $table->unsignedInteger('timestamp');

            $table->float('open', 20, 10);
            $table->float('close', 20, 10);
            $table->float('high', 20, 10);
            $table->float('low', 20, 10);

            $table->foreign('market_id')->references('id')->on('markets');

            $table->unique(['market_id', 'timestamp']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('market_prices');
    }
}
