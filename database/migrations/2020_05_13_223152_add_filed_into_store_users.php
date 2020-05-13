<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFiledIntoStoreUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('store_users',function (Blueprint $table) {
            $table->tinyInteger('role')->default(0)->comment("角色类型：0.商家；1.员工");
            $table->char('invite_code', 10)->comment('邀请码');
            $table->integer('invite_id')->default(0)->comment('邀请人');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('store_users', function (Blueprint $table) {
            $table->dropColumn('role');
            $table->dropColumn('invite_code');
            $table->dropColumn('invite_id');
        });
    }
}
