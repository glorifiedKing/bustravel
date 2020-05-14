<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRouteTypeToBookings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public $tableName='bookings';
    public $columnName='route_type';
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
          if (!Schema::hasColumn($this->tableName, $this->columnName)) {
              $table->string($this->columnName)->default('main_route');

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
        Schema::table($this->tableName, function (Blueprint $table) {
            $table->dropColumn($this->columnName);
        });
    }
}
