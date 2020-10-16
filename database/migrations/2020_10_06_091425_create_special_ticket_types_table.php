<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpecialTicketTypesTable extends Migration
{
  public $tableName='special_ticket_categories';
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
            $table->string('ticket_name');
            $table->string('ticket_type')->default('single_use');
            $table->decimal('price',13,2)->default('0.00');
            $table->string('routes')->nullable();            
            $table->timestamps();
            $table->unique(['operator_id','ticket_type','routes']);
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
