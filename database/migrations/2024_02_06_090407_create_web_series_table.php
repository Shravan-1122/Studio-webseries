<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWebSeriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('webseries', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('title');
            $table->text('description');
            $table->foreignId('theme_id')->constrained('themes');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->string('created_by');
            $table->string('updated_by')->nullable();
            $table->boolean('active')->default(true);
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
        Schema::dropIfExists('webseries');
    }
}