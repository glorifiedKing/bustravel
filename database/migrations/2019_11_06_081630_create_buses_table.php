<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('operator_id');
            $table->string('number_plate');
            $table->integer('seating_capacity');
            $table->text('description')->nullable();
            $table->text('seating_arrangement')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
            $table->foreign('operator_id')->references('id')->on('operators')->onDelete('cascade');
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('buses');
    }
}
