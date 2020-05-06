<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTypeToMarketPricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('market_prices', function (Blueprint $table) {
            $table->enum('type', ['minute', 'hour', 'day', 'month', 'year'])->default('minute')->after('timestamp');

            $table->index(['market_id']);
            $table->index(['timestamp']);
            $table->index(['type']);
        });

        Schema::table('market_prices', function (Blueprint $table) {
            $table->dropUnique(['market_id', 'timestamp']);
        });

        Schema::table('market_prices', function (Blueprint $table) {
            $table->unique(['market_id', 'timestamp', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

//        Schema::table('market_prices', function (Blueprint $table) {
//
//            $table->dropIndex(['market_id']);
//            $table->dropIndex(['timestamp']);
//            $table->dropIndex(['type']);
//        });

        Schema::table('market_prices', function (Blueprint $table) {
            $table->dropUnique(['market_id', 'timestamp', 'type']);
        });

        Schema::table('market_prices', function (Blueprint $table) {
            $table->dropColumn('type');

            $table->unique(['market_id', 'timestamp']);
        });
    }
}
