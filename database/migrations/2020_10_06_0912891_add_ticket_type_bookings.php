<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTicketTypeBookings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
     public $ticketTypeBookingtableName='bookings', $ticketTypeBookingcolumnName='ticket_type';
    public function up()
    {
        Schema::table($this->ticketTypeBookingtableName, function (Blueprint $table) {
            if (!Schema::hasColumn($this->ticketTypeBookingtableName, $this->ticketTypeBookingcolumnName)) {
                $table->integer($this->ticketTypeBookingcolumnName)->nullable();
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
        Schema::table($this->ticketTypeBookingtableName, function (Blueprint $table) {
            $table->dropColumn($this->ticketTypeBookingcolumnName);
        });
    }
}
