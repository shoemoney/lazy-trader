<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCoinmarketcapDataToExchanges extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('exchanges', function (Blueprint $table) {
            $table->dateTime('date_launched')->after('description')->nullable();
            $table->string('fee_url')->after('internal_name')->nullable();
            $table->string('chat_url')->after('internal_name')->nullable();
            $table->string('blog_url')->after('internal_name')->nullable();
            $table->string('twitter_url')->after('internal_name')->nullable();
            $table->string('url')->after('internal_name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('exchanges', function (Blueprint $table) {
            //
        });
    }
}
