<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSeatFormat extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
     public $seatFormattableName='buses', $seatFormatcolumnName='seating_format';
    public function up()
    {
        Schema::table($this->seatFormattableName, function (Blueprint $table) {
            if (!Schema::hasColumn($this->seatFormattableName, $this->seatFormatcolumnName)) {
                $table->string($this->seatFormatcolumnName)->default('3x2');
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
        Schema::table($this->seatFormattableName, function (Blueprint $table) {
            $table->dropColumn($this->seatFormatcolumnName);
        });
    }
}
