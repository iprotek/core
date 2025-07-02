<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropUserAdminPayAccountUnique extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        if(Schema::hasTable('user_admin_pay_accounts')) { 
            Schema::table('user_admin_pay_accounts', function (Blueprint $table) {
                $table->dropUnique('user_admin_pay_accounts_user_admin_id_unique');
            }); 
        };
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
