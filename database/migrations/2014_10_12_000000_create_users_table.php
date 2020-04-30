<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nickname')->default("");
            $table->char('phone', 11)->unique()->default("");

            $table->string('weapp_openid', 32)->default("");
            $table->string('province', 20)->default("");
            $table->string('city',20)->nullable();

            $table->tinyInteger('gender')->default(0);
            $table->string('avatar_url')->default("");
            $table->integer('parent_id')->default(0);
            $table->integer('store_id')->default(0);
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
        Schema::dropIfExists('users');
    }
}
