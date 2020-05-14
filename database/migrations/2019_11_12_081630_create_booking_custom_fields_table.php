<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingCustomFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
     public $tableName='booking_custom_fields';
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('operator_id');
            $table->string('field_prefix');
            $table->string('field_name');
            $table->integer('field_order')->default(0);
            $table->tinyInteger('is_required')->default(1);
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
            $table->foreign('operator_id')->references('id')->on('operators')->onDelete('cascade');
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
