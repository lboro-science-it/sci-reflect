<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDescriptorsTable extends Migration
{
    /**
     * Run the migrations.
     * Descriptors contain text which is shown for a given choice for a given
     * skill, when the choice is hovered.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('descriptors', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('activity_id');
            $table->integer('choice_id');
            $table->integer('skill_id');
            $table->string('text')->nullable();
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
        Schema::dropIfExists('descriptors');
    }
}
