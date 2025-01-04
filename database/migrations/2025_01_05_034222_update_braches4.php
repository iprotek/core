<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateBraches4 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        
        if(Schema::hasTable('branches')) { 
            
            if (!Schema::hasColumn('branches', 'data')) {
                Schema::table('branches', function (Blueprint $table) {
                    $table->longText('data')->nullable();
                });
            } 
            if (!Schema::hasColumn('branches', 'is_active')) {
                Schema::table('branches', function (Blueprint $table) {
                    $table->boolean('is_active')->default(1);
                });
            } 
            if (!Schema::hasColumn('branches', 'deleted_at')) {
                Schema::table('branches', function (Blueprint $table) {
                    $table->dateTime('deleted_at')->nullable();
                });
            } 
            return;
        } 
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
