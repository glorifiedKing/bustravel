<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStopoverRoutesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stopover_routes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('route_id');
            $table->string('stopover_id');
            $table->integer('order')->default(0);
            $table->timestamps();
            $table->foreign('route_id')->references('id')->on('routes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bookings_fields');
    }
}
