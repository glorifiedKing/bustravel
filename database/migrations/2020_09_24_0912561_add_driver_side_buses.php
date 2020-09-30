<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDriverSideBuses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
     public $driverSidetableName='buses', $driverSidecolumnName='driver_side';
    public function up()
    {
        Schema::table($this->driverSidetableName, function (Blueprint $table) {
            if (!Schema::hasColumn($this->driverSidetableName, $this->driverSidecolumnName)) {
                $table->string($this->driverSidecolumnName)->default('left');
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
        Schema::table($this->driverSidetableName, function (Blueprint $table) {
            $table->dropColumn($this->driverSidecolumnName);
        });
    }
}
