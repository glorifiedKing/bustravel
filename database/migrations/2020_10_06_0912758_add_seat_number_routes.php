<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSeatNumberRoutes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
     public $seatNumbertableName='routes', $seatNumbercolumnName='enable_seat_number_booking';
    public function up()
    {
        Schema::table($this->seatNumbertableName, function (Blueprint $table) {
            if (!Schema::hasColumn($this->seatNumbertableName, $this->seatNumbercolumnName)) {
                $table->integer($this->seatNumbercolumnName)->default(0);
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
        Schema::table($this->seatNumbertableName, function (Blueprint $table) {
            $table->dropColumn($this->seatNumbercolumnName);
        });
    }
}
