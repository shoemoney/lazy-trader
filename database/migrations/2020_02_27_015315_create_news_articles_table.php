<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewsArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('news_articles', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('source_id');

            $table->string('title');
            $table->longText('body');
            $table->timestamp('published_on')->nullable();
            $table->string('url')->nullable();
            $table->string('image_url')->nullable();

            $table->timestamps();

            $table->foreign('source_id')->references('id')->on('news_sources');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('news_articles');
    }
}
