<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoutesStopoversDepartureTimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('routes_stopovers_departure_times', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('routes_times_id');
            $table->integer('route_stopover_id');
            $table->string('arrival_time');
            $table->string('departure_time');
            $table->timestamps();
            $table->foreign('routes_times_id')->references('id')->on('routes_departure_times')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('routes_stopovers_departure_times');
    }
}
