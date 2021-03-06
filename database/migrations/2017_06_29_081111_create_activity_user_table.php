<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivityUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activity_user', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('activity_id');
            $table->integer('user_id');
            $table->enum('role', ['staff', 'student'])->default('student');
            $table->integer('lti_user_id')->nullable();
            $table->integer('current_page')->default(1)->nullable();
            $table->integer('current_round')->default(1)->nullable();
            $table->boolean('complete')->default(false);
            $table->integer('group_id')->nullable();
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
        Schema::dropIfExists('activity_user');
    }
}
