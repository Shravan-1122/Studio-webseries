<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSeasonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seasons', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('season_title');
            $table->text('description');
            $table->string('web_id');
            $table->string('created_by');
            $table->string('updated_by')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();

            $table->foreign('web_id')->references('id')->on('web_series');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('seasons');
    }
}