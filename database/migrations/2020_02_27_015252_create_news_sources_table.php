<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewsSourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('news_sources', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('name');
            $table->string('internal_name');
            $table->string('language')->nullable();
            $table->string('url')->nullable();
            $table->string('image_url')->nullable();

            $table->timestamps();

            $table->unique('internal_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('news_sources');
    }
}
