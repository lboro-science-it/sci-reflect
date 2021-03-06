<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 255)->nullable();
            $table->integer('resource_link_record_id')->nullable();
            $table->integer('consumer_pk')->nullable();
            $table->dateTime('open_date')->nullable();
            $table->dateTime('close_date')->nullable();
            $table->enum('status', ['new', 'design', 'open', 'closed'])->default('new');
            $table->enum('format', ['linear', 'nonlinear'])->default('linear');
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
        Schema::dropIfExists('activities');
    }
}
