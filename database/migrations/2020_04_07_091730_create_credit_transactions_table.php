<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCreditTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public $tableName='credit_transactions';
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->decimal('amount',12,2);
            $table->string('payment_method')->default('mobile_money');
            $table->string('payment_gateway')->default('palm_kash');
            $table->string('payment_gateway_reference')->nullable();
            $table->string('payment_gateway_result')->nullable();
            $table->string('payee_reference');
            $table->string('payee_reference_2')->nullable();
            $table->string('payee_reference_3')->nullable();
            $table->string('status')->default('pending');
            $table->string('status_reference')->nullable();
            $table->bigInteger('transaction_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->tableName);
    }
}
