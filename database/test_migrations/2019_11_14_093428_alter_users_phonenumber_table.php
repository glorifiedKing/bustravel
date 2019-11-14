<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterUsersPhonenumberTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

      Schema::table('users', function($table) {
      if(!Schema::hasColumn('users', 'phone_number'))
      {
            $table->string('phone_number')->nullable();
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
      Schema::table('users', function (Blueprint $table) {
         $table->dropColumn('phone_number');
       });
    }
}
