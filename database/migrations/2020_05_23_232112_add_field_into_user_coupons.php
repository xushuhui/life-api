<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldIntoUserCoupons extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_coupons',function (Blueprint $table) {
            $table->tinyInteger('coupon_type')->default(1)->comment('优惠券类型：1.普通券；2.联盟券；3.次卡券；4.储值券');
            $table->decimal('total_nums',10, 2)->comment('卡券：总次数；储值卡：总额度');
            $table->decimal('surplus_nums',10, 2)->comment('卡券：剩余次数；储值卡：剩余额度');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->dropColumn('coupon_type');
            $table->dropColumn('total_nums');
            $table->dropColumn('surplus_nums');
        });
    }
}
