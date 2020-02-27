<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoinTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coin_tag', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('coin_id');
            $table->unsignedBigInteger('tag_id');

            $table->foreign('coin_id')->references('id')->on('coins');
            $table->foreign('tag_id')->references('id')->on('tags');

            $table->unique(['coin_id', 'tag_id']);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coin_tag');
    }
}
