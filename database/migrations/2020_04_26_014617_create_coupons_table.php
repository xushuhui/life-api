<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->integer('store_id');
            $table->string('coupon_name',15)->comment('优惠券名称');
            $table->tinyInteger('coupon_type')->default(1)->comment('优惠券类型：1.普通券；2.联盟券；3.次卡券；4.储值券');
            $table->timestamp('end_time')->comment('结束时间');
            $table->integer('total_num')->unsigned()->default(0)->comment('总次数');
            $table->integer('user_num')->unsigned()->default(0)->comment('每个人可用使用该优惠券的次数');
            // 是否推荐，点击了推荐，数据将被推送到小程序首页；默认为1次推荐，最新推荐将覆盖最老的推荐。
            $table->boolean('is_rec')->unsigned()->default(0)->comment('是否推荐：0.否；1.是');
            $table->string('coupon_explain',256)->comment('说明');
            $table->string('use_notice',256)->comment('使用须知');
            $table->string('careful_matter',256)->comment('注意事项');
            $table->decimal('recharge_amount',10, 2)->comment('充值金额');
            $table->decimal('give_amount',10, 2)->comment('赠送金额');
            $table->timestamps();
            $table->softDeletes();
            $table->index(['store_id']);
            $table->index(['coupon_type']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coupons');
    }
}
