<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRouteTrackingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('route_trackings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('routes_times_id');
            $table->integer('driver_id');
            $table->integer('bus_id');
            $table->string('start_time')->nullable();
            $table->tinyInteger('started')->default(0);
            $table->string('end_time')->nullable();
            $table->tinyInteger('ended')->default(0);
            $table->date('date_of_travel')->nullable();
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
        Schema::dropIfExists('route_trackings');
    }
}
