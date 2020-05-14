<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOperatorPaymentMethodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
     public $tableName='operator_payment_methods';
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('operator_id');
            $table->string('payment_method');
            $table->string('palm_wallet')->nullable();
            $table->string('sp_phone_number')->nullable();
            $table->string('mobile_money_operator')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('bank_account_name')->nullable();
            $table->string('bank_name')->nullable();
            $table->boolean('is_default')->default(0);
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
