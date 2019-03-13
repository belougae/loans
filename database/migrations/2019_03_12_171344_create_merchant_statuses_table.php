<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMerchantStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('merchant_statuses', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('merchant_id');
            $table->foreign('merchant_id')->references('id')->on('merchants')->onDelete('cascade');
            $table->string('type')->default(\App\Models\MerchantStatuses::TYPE_INDEX)->comment('类型');
            $table->enum('putaway', [0, 1])->default(0)->comment('0:下架 1:上架');
            $table->unsignedInteger('sort')->nullable()->comment('序号');
            $table->tinyInteger('top')->default(0)->comment('0：不置顶 1:置顶');
            $table->timestamps();
        });
        \DB::statement("ALTER TABLE `merchant_statuses` comment '商户状态信息表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('merchant_statuses');
    }
}
