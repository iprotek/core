<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateFileUploads extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    { 
        
        if(Schema::hasTable('file_uploads')) { 
            
            if (!Schema::hasColumn('file_uploads', 'group_id')) {
                Schema::table('file_uploads', function (Blueprint $table) {
                    $table->BigInteger('group_id')->nullable();
                });
            }
            if (!Schema::hasColumn('file_uploads', 'pay_created_by')) {
                Schema::table('file_uploads', function (Blueprint $table) {
                    $table->BigInteger('pay_created_by')->nullable();
                });
            }
            if (!Schema::hasColumn('file_uploads', 'pay_updated_by')) {
                Schema::table('file_uploads', function (Blueprint $table) {
                    $table->BigInteger('pay_updated_by')->nullable();
                });
            }
            if (!Schema::hasColumn('file_uploads', 'pay_deleted_by')) {
                Schema::table('file_uploads', function (Blueprint $table) {
                    $table->BigInteger('pay_deleted_by')->nullable();
                });
            }
            return;
        } 

        Schema::table('file_uploads', function (Blueprint $table) {
            $table->bigInteger('group_id')->nullable();
            $table->bigInteger('pay_created_by')->nullable(); 
            $table->bigInteger('pay_updated_by')->nullable();
            $table->bigInteger('pay_deleted_by')->nullable();
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
