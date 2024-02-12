<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEpisodeArtistsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('episode_artist', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('episode_id');
            $table->unsignedBigInteger('artist_id');
            $table->timestamps();

            $table->foreign('episode_id')->references('id')->on('episodes');
            $table->foreign('artist_id')->references('id')->on('artists');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('episode_artist');
    }
}