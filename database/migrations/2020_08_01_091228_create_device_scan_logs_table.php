<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeviceScanLogsTable extends Migration
{
  public $tableNameScannerLog='device_scan_logs';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableNameScannerLog, function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('device_id');
            $table->string('ticket_number')->nullable();
            $table->string('result')->nullable();
            $table->text('request_attributes');
            $table->string('ip_address');                        
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
        Schema::dropIfExists($this->tableNameScannerLog);
    }
}
