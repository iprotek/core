<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFileUploadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        try{
            if(Schema::hasTable('file_uploads')) {
                // Table exists
                
                Schema::table('file_uploads', function (Blueprint $table) {
                    $table->string('target_name')->nullable();
                    $table->string('target_id')->nullable();
                    $table->integer('order_no')->nullable();
                    $table->string('file_type')->nullable();
                    $table->string('file_name')->nullable();
                    $table->string('file_ext')->nullable();
                    $table->boolean('is_default')->nullable();
                    $table->text('location')->nullable();
                    $table->integer('created_by')->nullable();
                    $table->integer('deleted_by')->nullable();
                });

                return;
            }
        }catch(\Exception $ex){
            return;
        }



        Schema::create('file_uploads', function (Blueprint $table) {
            $table->id();
            $table->string('target_name');
            $table->string('target_id');
            $table->integer('order_no');
            $table->string('file_type');
            $table->string('file_name');
            $table->string('file_ext');
            $table->boolean('is_default')->nullable();
            $table->text('location')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('deleted_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('file_uploads');
    }
}
