<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoundsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rounds', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('activity_id')->nullable();
            $table->integer('inherit_from_round_id')->nullable();
            $table->enum('format', ['linear', 'nonlinear'])->default('linear');
            $table->boolean('keep_visible')->default(true);
            $table->dateTime('open_date')->nullable();
            $table->dateTime('close_date')->nullable();
            $table->integer('round_number')->nullable();
            $table->string('title', 50)->nullable();
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
        Schema::dropIfExists('rounds');
    }
}
