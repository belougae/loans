<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMerchantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('merchants', function (Blueprint $table) {
            $table->increments('id');
            $table->string('thumbnail')->nullable()->comment('商户图标');
            $table->string('key_name')->unique()->comment('商户标识');
            $table->string('name')->comment('商户名称');
            $table->unsignedInteger('max_limit')->comment('最高额度');
            $table->string('description')->nullable()->comment('车品描述');
            $table->float('rate')->nullable()->comment('利息低至');
            $table->string('url')->nullable()->comment('推广 URL');
            $table->string('label_first')->nullable()->comment('标签1');
            $table->string('label_second')->nullable()->comment('标签2');
            $table->string('label_third')->nullable()->comment('标签3');
            $table->unsignedInteger('count_click')->default(0)->comment('点击数');
            $table->timestamps();
        });
        \DB::statement("ALTER TABLE `merchants` comment '商户信息登记表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('merchants');
    }
}
