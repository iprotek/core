<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUserAdminWithActive extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        if(Schema::hasTable('user_admins')) { 
            
            if (!Schema::hasColumn('user_admins', 'is_active')) {
                Schema::table('user_admins', function (Blueprint $table) {
                    $table->boolean('is_active')->default(1);
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
