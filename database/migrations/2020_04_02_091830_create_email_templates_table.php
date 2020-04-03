<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmailTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_templates', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('operator_id');
            $table->string('purpose')->default('TICKET');
            $table->string('language')->default('english');
            $table->text('message');
            $table->boolean('is_default')->default(0);                       
            $table->timestamps();
            $table->unique(['operator_id','purpose','language']); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('email_templates');
    }
}
