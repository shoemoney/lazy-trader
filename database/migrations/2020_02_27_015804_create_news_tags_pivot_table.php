<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewsTagsPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('news_article_tag', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('news_article_id');
            $table->unsignedBigInteger('tag_id');

            $table->foreign('news_article_id')->references('id')->on('news_articles');
            $table->foreign('tag_id')->references('id')->on('tags');

            $table->unique(['news_article_id', 'tag_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('news_tags');
    }
}
