<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTicketValidDateBookings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
     public $ticketValiditytableName='bookings', $ticketValiditycolumnName='valid_until_date';
    public function up()
    {
        Schema::table($this->ticketValiditytableName, function (Blueprint $table) {
            if (!Schema::hasColumn($this->ticketValiditytableName, $this->ticketValiditycolumnName)) {
                $table->date($this->ticketValiditycolumnName)->nullable();
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
        Schema::table($this->ticketValiditytableName, function (Blueprint $table) {
            $table->dropColumn($this->ticketValiditycolumnName);
        });
    }
}
