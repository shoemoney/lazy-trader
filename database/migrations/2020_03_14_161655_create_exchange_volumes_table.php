<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExchangeVolumesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exchange_volumes', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('exchange_id');
            $table->unsignedBigInteger('coin_id');

            $table->enum('type', [
                'HOURLY', 'DAILY'
            ]);
            $table->unsignedInteger('timestamp');

            $table->unsignedFloat('volume', 30, 10);

            $table->foreign('coin_id')->references('id')->on('coins');
            $table->foreign('exchange_id')->references('id')->on('exchanges');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('exchange_volumes');
    }
}
