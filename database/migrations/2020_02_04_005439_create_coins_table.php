<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoinsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coins', function (Blueprint $table) {
            $table->bigIncrements('id');

            // symbol
            $table->string('symbol');
            // TODO: Verify symbols are only 8 characters or less.

            // coin name
            $table->string('name');

            // coin full name
            $table->string('full_name');

            // coin image url
            $table->string('image_url')->nullable();
            // TODO: Turn this into locally stored images.

            // proof type (nullable)
            $table->string('proof_type')->nullable();

            // weiss rating
            $table->string('weiss_rating')->nullable();

            // weiss technology adoption rating
            $table->string('weiss_technology_adoption_rating')->nullable();

            // weiss market performance rating
            $table->string('weiss_performance_rating')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coins');
    }
}
