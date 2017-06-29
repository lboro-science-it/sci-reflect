<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLti2UserResultTable extends Migration
{
    /**
     * Run the migrations.
     * Note default lengths removed from following due to production MySQL version:
     * lti_user_id (255)
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lti2_user_result', function (Blueprint $table) {
        // adapted from https://github.com/EonConsulting/laravel-lti/blob/master/src/database/migrations/2017_01_17_000000_create_lti_tables.php
            $table->increments('user_pk');
            $table->integer('resource_link_pk')->index()->unsigned();
            $table->text('lti_user_id');                    // changed from varchar
            $table->text('lti_result_sourcedid');           // changed from varchar
            $table->dateTime('created');
            $table->dateTime('updated');
            $table->timestamps();
        });

        Schema::table('lti2_user_result', function (Blueprint $table) {
            $table->foreign('resource_link_pk')->references('resource_link_pk')->on('lti2_resource_link');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lti2_user_result');
    }
}
