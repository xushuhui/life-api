<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldIntoOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders',function (Blueprint $table) {
            $table->decimal('use_nums',10, 2)->comment('卡券：使用次数（带好友就可能是多次了）；储值卡：使用额度');
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
            $table->dropColumn('use_nums');
            $table->dropColumn('surplus_nums');
        });
    }
}
