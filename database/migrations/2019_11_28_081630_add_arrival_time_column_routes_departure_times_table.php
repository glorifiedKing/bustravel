<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddArrivalTimeColumnRoutesDepartureTimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('routes_departure_times', function (Blueprint $table) {
            if (!Schema::hasColumn('routes_departure_times', 'arrival_time')) {
                $table->string('arrival_time')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('routes_departure_times', function (Blueprint $table) {
            $table->dropColumn('arrival_time');
        });
    }
}
