<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWorkstationColumnUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public $tableName='users';
    public $columnName='workstation';
    public function up()
    {       
            

        Schema::table($this->tableName, function ($table) {
            if (!Schema::hasColumn($this->tableName, $this->columnName)) {
                $table->unsignedBigInteger($this->columnName)->nullable();
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
