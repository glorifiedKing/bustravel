<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLanguageColumnPaymentTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
     public $tableName='payment_transactions', $columnName='language';
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            if (!Schema::hasColumn($this->tableName, $this->columnName)) {
                $table->string($this->columnName,50)->default('english');
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
