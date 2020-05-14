<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
     public $tableName='payment_transactions';
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
            $table->bigInteger('user_id');
            $table->text('main_routes')->nullable();
            $table->text('stop_over_routes')->nullable();
            $table->boolean('send_email')->default(0);
            $table->boolean('send_sms')->default(0);
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('email')->nullable();
            $table->string('address_1')->nullable();
            $table->string('address_2')->nullable();
            $table->string('country')->nullable();
            $table->date('date_of_travel');
            $table->bigInteger('transport_operator_id');
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
