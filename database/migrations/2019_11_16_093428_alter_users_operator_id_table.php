<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterUsersOperatorIdTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public $tableName='users';
    public $columnName='operator_id';
    public function up()
    {
          
        Schema::table($this->tableName, function ($table) {
            if (!Schema::hasColumn($this->tableName, $this->columnName)) {
                $table->integer($this->columnName)->default(0);
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
