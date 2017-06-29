<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLti2ConsumerTable extends Migration
{
    /**
     * Run the migrations.
     * Note default lengths removed from following due to production MySQL version:
     * consumer_key256 (256), consumer_name (255), consumer_version (255), consumer_guid (1024)
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lti2_consumer', function (Blueprint $table) {
        // adapted from https://github.com/EonConsulting/laravel-lti/blob/master/src/database/migrations/2017_01_17_000000_create_lti_tables.php
            $table->increments('consumer_pk');
            $table->string('name', 50);
            $table->string('consumer_key256', 191)->unique();   // length shortened
            $table->text('consumer_key')->nullable();
            $table->string('secret', 1024);
            $table->string('lti_version', 10)->nullable();
            $table->text('consumer_name')->nullable();          // changed from varchar
            $table->text('consumer_version')->nullable();       // changed from varchar
            $table->text('consumer_guid')->nullable();          // changed from varchar
            $table->text('profile')->nullable();
            $table->text('tool_proxy')->nullable();
            $table->text('settings')->nullable();
            $table->tinyInteger('protected')->default(0);
            $table->tinyInteger('enabled')->default(0);
            $table->dateTime('enable_from')->nullable();
            $table->dateTime('enable_until')->nullable();
            $table->date('last_access')->nullable();
            $table->dateTime('created')->nullable();
            $table->dateTime('updated')->nullable();

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
        Schema::dropIfExists('lti2_consumer');
    }
}
