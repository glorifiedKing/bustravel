<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTicketValidCountBookings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
     public $ticketValidityCounttableName='bookings', $ticketValidityCountcolumnName='valid_until_count';
    public function up()
    {
        Schema::table($this->ticketValidityCounttableName, function (Blueprint $table) {
            if (!Schema::hasColumn($this->ticketValidityCounttableName, $this->ticketValidityCountcolumnName)) {
                $table->integer($this->ticketValidityCountcolumnName)->nullable();
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
        Schema::table($this->ticketValidityCounttableName, function (Blueprint $table) {
            $table->dropColumn($this->ticketValidityCountcolumnName);
        });
    }
}
