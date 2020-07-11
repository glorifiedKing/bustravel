<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaymentTransactionidBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
     public $tableNameNew='bookings', $columnNameNew='payment_transaction_id';
    public function up()
    {
        Schema::table($this->tableNameNew, function (Blueprint $table) {
            if (!Schema::hasColumn($this->tableNameNew, $this->columnNameNew)) {
                $table->string($this->columnNameNew)->nullable();
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
        Schema::table($this->tableNameNew, function (Blueprint $table) {
            $table->dropColumn($this->columnNameNew);
        });
    }
}
