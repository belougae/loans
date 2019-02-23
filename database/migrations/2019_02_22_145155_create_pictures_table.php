<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePicturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pictures', function (Blueprint $table) {
            $table->increments('id');
            $table->string('thumbnail')->comment('图片路径');
            $table->string('name')->nullable()->comment('图片名称');
            $table->string('url')->nullable()->comment('URL');
            $table->tinyInteger('type')->nullable()->comment('设备类型 0:其他 1:首页导航栏 2:最新下款王 3:首页顶部');
            $table->timestamps();
        });
        \DB::statement("ALTER TABLE `pictures` comment '商户信息登记表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pictures');
    }
}
