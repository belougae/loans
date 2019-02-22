<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('phone')->unique()->comment('手机号码');
            $table->tinyInteger('device_type')->nullable()->comment('设备类型 0:h5 1:ios 2:android');
            $table->tinyInteger('status')->default(1)->comment('用户状态 0：锁定 1：未激活 2：已激活');
            $table->tinyInteger('channel_id')->default(0)->comment('渠道ID 默认0:自有平台');
            $table->rememberToken();
            $table->timestamps();
        });
        \DB::statement("ALTER TABLE `users` comment '用户信息登记表'");
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
