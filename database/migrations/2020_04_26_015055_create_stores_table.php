<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stores', function (Blueprint $table) {
            $table->id()->comment('商家表');
            $table->string("name", 15)->comment("店铺名称");
            $table->string("logo")->default("")->comment("店铺logo");
            $table->string("photo")->default("")->comment("店铺照片");
            $table->string("intro")->default("")->comment("介绍");
            $table->tinyInteger("type")->default(0)->comment("商家类型");
            $table->tinyInteger("status")->default(1)->comment("状态：1.正常");
            $table->timestamp("expire_at")->comment("过期时间");

            $table->char('store_mobile', 11)->comment('手机号');
            $table->string('password', 60)->comment('密码');
            $table->char('invite_code', 10)->comment('邀请码');
            $table->integer('invite_id')->comment('邀请人');

            $table->timestamps();
            $table->softDeletes();
            $table->index(['store_mobile']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stores');
    }
}
