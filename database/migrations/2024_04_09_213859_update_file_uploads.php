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
        try{
           
            Schema::table('file_uploads', function (Blueprint $table) {
                $table->bigInteger('group_id')->nullable();
                $table->bigInteger('pay_created_by')->nullable(); 
                $table->bigInteger('pay_updated_by')->nullable();
                $table->bigInteger('pay_deleted_by')->nullable();
            });

            return;

        }catch(\Exception $ex){
            return;
        }
        //
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
