<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketScannersTable extends Migration
{
  public $tableNameScanner='ticket_scanners';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableNameScanner, function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('device_id',160)->unique();
            $table->text('description')->nullable();
            $table->text('ip_addresses')->nullable();
            $table->unsignedBigInteger('operator_id');
            $table->tinyInteger('active')->default(1);            
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
        Schema::dropIfExists($this->tableNameScanner);
    }
}
