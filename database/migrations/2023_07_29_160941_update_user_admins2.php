<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUserAdmins2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        if(Schema::hasTable('user_admin_infos')) { 
            if (!Schema::hasColumn('user_admin_infos', 'is_active')) {
                Schema::table('user_admin_infos', function (Blueprint $table) {
                    $table->integer('is_active')->default(1);
                });
            }
            return;
            
        }
        Schema::table('user_admins', function (Blueprint $table) {
            $table->integer('is_active')->default(1);
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
