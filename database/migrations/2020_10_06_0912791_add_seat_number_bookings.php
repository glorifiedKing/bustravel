<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSeatNumberBookings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
     public $seatNumberBookingtableName='bookings', $seatNumberBookingcolumnName='seat_number';
    public function up()
    {
        Schema::table($this->seatNumberBookingtableName, function (Blueprint $table) {
            if (!Schema::hasColumn($this->seatNumberBookingtableName, $this->seatNumberBookingcolumnName)) {
                $table->string($this->seatNumberBookingcolumnName)->nullable();
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
        Schema::table($this->seatNumberBookingtableName, function (Blueprint $table) {
            $table->dropColumn($this->seatNumberBookingcolumnName);
        });
    }
}
