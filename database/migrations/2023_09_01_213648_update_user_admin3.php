<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUserAdmin3 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        try{
           
            Schema::table('user_admins', function (Blueprint $table) {
                $table->integer('receive_email_notif')->default(0);
            });

            return;

        }catch(\Exception $ex){
            return;
        }

        //
        Schema::table('user_admins', function (Blueprint $table) {
            $table->integer('receive_email_notif')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
