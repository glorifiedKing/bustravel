<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFirstRowBuses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
     public $firstRowtableName='buses', $firstRowcolumnName='first_row_count';
    public function up()
    {
        Schema::table($this->firstRowtableName, function (Blueprint $table) {
            if (!Schema::hasColumn($this->firstRowtableName, $this->firstRowcolumnName)) {
                $table->integer($this->firstRowcolumnName)->default(1);
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
        Schema::table($this->firstRowtableName, function (Blueprint $table) {
            $table->dropColumn($this->firstRowcolumnName);
        });
    }
}
