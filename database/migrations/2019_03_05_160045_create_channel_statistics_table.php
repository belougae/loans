<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChannelStatisticsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('channel_statistics', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('channel_id')->comment('渠道id');
            $table->foreign('channel_id')->references('id')->on('constants')->onDelete('cascade');
            $table->unsignedInteger('count_visit')->default(0)->comment('访问次数');
            $table->unsignedInteger('count_register')->default(0)->comment('注册人数');
            $table->date('statistic_at')->comment('统计时间');
            $table->unsignedInteger('hour')->comment('时针');
            $table->timestamps();
        });
        \DB::statement("ALTER TABLE `merchant_statistics` comment '渠道统计表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('channel_statistics');
    }
}
