<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLti2ShareKeyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lti2_share_key', function (Blueprint $table) {
        // adapted from https://github.com/EonConsulting/laravel-lti/blob/master/src/database/migrations/2017_01_17_000000_create_lti_tables.php
            $table->string('share_key_id', 32)->unique()->primary();
            $table->integer('resource_link_pk')->index()->unsigned();
            $table->tinyInteger('auto_approve')->default(0);
            $table->dateTime('expires');
            $table->timestamps();
        });

        Schema::table('lti2_share_key', function (Blueprint $table) {
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
        Schema::dropIfExists('lti2_share_key');
    }
}
