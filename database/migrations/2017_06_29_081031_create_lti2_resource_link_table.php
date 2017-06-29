<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLti2ResourceLinkTable extends Migration
{
    /**
     * Run the migrations.
     * Note default lengths removed from following due to production MySQL version:
     * lti_resource_link_id (255)
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lti2_resource_link', function (Blueprint $table) {
        // adapted from https://github.com/EonConsulting/laravel-lti/blob/master/src/database/migrations/2017_01_17_000000_create_lti_tables.php
            $table->increments('resource_link_pk');
            $table->integer('context_pk')->nullable()->index()->unsigned();
            $table->integer('consumer_pk')->nullable()->index();
            $table->text('lti_resource_link_id', 255);          // changed from varchar
            $table->text('settings');
            $table->integer('primary_resource_link_pk')->nullable()->unsigned()->default(null);
            $table->tinyInteger('share_approved')->nullable()->default(null);
            $table->dateTime('created');
            $table->dateTime('updated');
            $table->engine = "InnoDB";
            $table->timestamps();
        });

        Schema::table('lti2_resource_link', function (Blueprint $table) {
            $table->foreign('context_pk')->references('context_pk')->on('lti2_context');
            $table->foreign('primary_resource_link_pk')->references('resource_link_pk')->on('lti2_resource_link');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lti2_resource_link');
    }
}
