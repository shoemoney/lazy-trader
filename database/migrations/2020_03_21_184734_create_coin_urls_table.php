<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoinUrlsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coin_urls', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('coin_id');
            $table->enum('type', ['website', 'technical_doc', 'twitter', 'reddit', 'message_board', 'announcement', 'chat', 'explorer', 'source_code']);

            $table->string('url');

            $table->timestamps();

            $table->foreign('coin_id')->references('id')->on('coins');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coin_urls');
    }
}
