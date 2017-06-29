<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLti2ContextTable extends Migration
{
    /**
     * Run the migrations.
     * Note default lengths removed from following due to production MySQL version:
     * lti_context_id (255)
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lti2_context', function (Blueprint $table) {
        // adapted from https://github.com/EonConsulting/laravel-lti/blob/master/src/database/migrations/2017_01_17_000000_create_lti_tables.php
            $table->increments('context_pk');
            $table->integer('consumer_pk')->unsigned()->index();
            $table->text('lti_context_id');     // changed from varchar
            $table->text('settings');
            $table->dateTime('created');
            $table->dateTime('updated');
            $table->timestamps();
        });

        Schema::table('lti2_context', function (Blueprint $table) {
            $table->foreign('consumer_pk')->references('consumer_pk')->on('lti2_consumer');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lti2_context');
    }
}
