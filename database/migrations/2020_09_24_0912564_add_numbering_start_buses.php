<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNumberingStartBuses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
     public $numberStarttableName='buses', $numberStartcolumnName='numbering_start';
    public function up()
    {
        Schema::table($this->numberStarttableName, function (Blueprint $table) {
            if (!Schema::hasColumn($this->numberStarttableName, $this->numberStartcolumnName)) {
                $table->string($this->numberStartcolumnName)->default('wall');
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
        Schema::table($this->numberStarttableName, function (Blueprint $table) {
            $table->dropColumn($this->numberStartcolumnName);
        });
    }
}
