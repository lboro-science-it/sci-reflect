<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLti2ToolProxyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lti2_tool_proxy', function (Blueprint $table) {
        // adapted from https://github.com/EonConsulting/laravel-lti/blob/master/src/database/migrations/2017_01_17_000000_create_lti_tables.php
            $table->increments('tool_proxy_pk');
            $table->string('tool_proxy_id', 32)->unique();
            $table->integer('consumer_pk')->unsigned()->index();
            $table->text('tool_proxy');
            $table->dateTime('created');
            $table->dateTime('updated');
            $table->timestamps();
        });

        Schema::table('lti2_tool_proxy', function (Blueprint $table) {
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
        Schema::dropIfExists('lti2_tool_proxy');
    }
}
