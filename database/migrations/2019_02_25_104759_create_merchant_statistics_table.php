<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMerchantStatisticsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('merchant_statistics', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('merchant_id')->comment('商户id');
            $table->foreign('merchant_id')->references('id')->on('merchants')->onDelete('cascade');
            $table->unsignedInteger('count')->default(0)->comment('累计次数');
            $table->timestamps();
        });
        \DB::statement("ALTER TABLE `merchant_statistics` comment '商户统计信息表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('merchant_statistics');
    }
}
