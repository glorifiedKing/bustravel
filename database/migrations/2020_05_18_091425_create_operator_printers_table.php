<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOperatorPrintersTable extends Migration
{
  public $tableName='operator_printers';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('operator_id');
            $table->string('printer_name')->default('palmkash_printer');
            $table->string('printer_url')->default('rawbt:base64');
            $table->string('printer_port')->default('9100');
            $table->boolean('is_default')->default(false);            
            $table->timestamps();
            $table->unique(['operator_id','printer_url']);
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
