<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserIdColumnRouteTrackingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
     public $trackingtableName='route_trackings', $trackingcolumnName='user_id';
    public function up()
    {
        Schema::table($this->trackingtableName, function (Blueprint $table) {
            if (!Schema::hasColumn($this->trackingtableName, $this->trackingcolumnName)) {
                $table->integer($this->trackingcolumnName)->default(0);
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
        Schema::table($this->trackingtableName, function (Blueprint $table) {
            $table->dropColumn($this->trackingcolumnName);
        });
    }
}
