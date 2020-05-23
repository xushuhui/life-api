<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_coupons', function (Blueprint $table) {
            $table->id();
            $table->string("name",6);
            $table->integer("user_id");
            $table->integer("coupon_id");
            $table->integer('store_id');
            $table->timestamp("use_at")->nullable();

            $table->tinyInteger("status")->default(1)->comment("用户优惠券状态，1已领取,2已使用,3已过期");
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
        Schema::dropIfExists('user_coupons');
    }
}
