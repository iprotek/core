<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserPayAccountBrowserSessionId extends Migration
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
            
            if (!Schema::hasColumn('user_admin_pay_accounts', 'browser_session_id')) {
                Schema::table('user_admin_pay_accounts', function (Blueprint $table) {
                    $table->string('browser_session_id', 200)->nullable();
                });
            } 
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
