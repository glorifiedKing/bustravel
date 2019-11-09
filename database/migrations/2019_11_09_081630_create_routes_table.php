<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoutesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('routes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('operator_id');
            $table->integer('start_station');
            $table->integer('end_station');
            $table->decimal('price',12,2)->default(0.00);
            $table->decimal('return_price',12,2)->default(0.00);
            $table->string('departure_time');
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
        Schema::dropIfExists('routes');
    }
}
