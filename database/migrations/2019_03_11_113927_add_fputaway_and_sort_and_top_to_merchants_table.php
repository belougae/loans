<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFputawayAndSortAndTopToMerchantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('merchants', function (Blueprint $table) {
            $table->enum('putaway', [0, 1])->default(0)->comment('上架 0:下架 1:上架');
            $table->unsignedInteger('sort')->default(0)->comment('序号');
            $table->enum('top', [0, 1])->default(0)->comment('置顶 0：不置顶 1:置顶');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('merchants', function (Blueprint $table) {
            $table->dropColumn('putaway');
            $table->dropColumn('sort');
            $table->dropColumn('top');
        });
    }
}
