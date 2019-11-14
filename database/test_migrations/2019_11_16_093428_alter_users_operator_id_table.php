<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterUsersOperatorIdTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

      Schema::table('users', function($table) {


   if(!Schema::hasColumn('users', 'operator_id'))
      {
          $table->integer('operator_id')->default(0);
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
         $table->dropColumn('operator_id');
       });
    }
}
