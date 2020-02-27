<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FlipBaseAndQuoteColumnsOnCoinPairs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('coin_pairs', function (Blueprint $table) {
            $table->renameColumn('base_coin_id', 'temp_quote_coin_id');
        });
        Schema::table('coin_pairs', function (Blueprint $table) {
            $table->renameColumn('quote_coin_id', 'base_coin_id');
        });
        Schema::table('coin_pairs', function (Blueprint $table) {
            $table->renameColumn('temp_quote_coin_id', 'quote_coin_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('coin_pairs', function (Blueprint $table) {
            $table->renameColumn('quote_coin_id', 'temp_quote_coin_id');
        });
        Schema::table('coin_pairs', function (Blueprint $table) {
            $table->renameColumn('base_coin_id', 'quote_coin_id');
        });
        Schema::table('coin_pairs', function (Blueprint $table) {
            $table->renameColumn('temp_quote_coin_id', 'base_coin_id');
        });
    }
}
