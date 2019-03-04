<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatisticAtAndHourTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('merchant_statistics', function (Blueprint $table) {
            $table->dateTime('statistic_at')->after('count');
            $table->unsignedInteger('hour')->after('statistic_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('merchant_statistics', function (Blueprint $table) {
            $table->dropColumn('statistic_at');
            $table->dropColumn('hour');
        });
    }
}
