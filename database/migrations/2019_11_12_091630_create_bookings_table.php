<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
     public $tableName='bookings';
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('routes_departure_time_id');
            $table->decimal('amount', 12, 2)->default(0.00);
            $table->date('date_paid')->nullable();
            $table->date('date_of_travel')->nullable();
            $table->string('time_of_travel')->nullable();
            $table->string('ticket_number');
            $table->integer('user_id')->default(0);
            $table->tinyInteger('status')->default(1);
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->tableName);
    }
}
