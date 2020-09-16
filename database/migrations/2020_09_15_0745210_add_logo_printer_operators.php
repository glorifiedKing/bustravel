<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLogoPrinterOperators extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
     public $opstableName='operators', $opscolumnName='logo_printer';
    public function up()
    {
        Schema::table($this->opstableName, function (Blueprint $table) {
            if (!Schema::hasColumn($this->opstableName, $this->opscolumnName)) {
                $table->string($this->opscolumnName)->nullable();
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
        Schema::table($this->opstableName, function (Blueprint $table) {
            $table->dropColumn($this->opscolumnName);
        });
    }
}
