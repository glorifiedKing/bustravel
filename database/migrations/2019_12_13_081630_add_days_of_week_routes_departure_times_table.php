<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDaysOfWeekRoutesDepartureTimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('routes_departure_times', function (Blueprint $table) {
            if (!Schema::hasColumn('routes_departure_times', 'days_of_week')) {
                $table->json('days_of_week')->nullable();
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
            $table->dropColumn('days_of_week');
        });
    }
}
