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
         
            if(Schema::hasTable('file_uploads')) {

                // TARGET NAME
                if (!Schema::hasColumn('file_uploads', 'target_name')) {
                    Schema::table('file_uploads', function (Blueprint $table) {
                        $table->string('target_name')->nullable();
                    });
                }
                //TARGET ID
                if (!Schema::hasColumn('file_uploads', 'target_id')) {
                    Schema::table('file_uploads', function (Blueprint $table) {
                        $table->string('target_id')->nullable();
                    });
                }
                //ORDER NO
                if (!Schema::hasColumn('file_uploads', 'order_no')) {
                    Schema::table('file_uploads', function (Blueprint $table) { 
                        $table->string('order_no')->nullable();
                    });
                }
                //FILE TYPE
                if (!Schema::hasColumn('file_uploads', 'file_type')) {
                    Schema::table('file_uploads', function (Blueprint $table) { 
                        $table->string('file_type')->nullable();
                    });
                }
                //FILE NAME
                if (!Schema::hasColumn('file_uploads', 'file_name')) {
                    Schema::table('file_uploads', function (Blueprint $table) { 
                        $table->string('file_name')->nullable();
                    });
                }
                //FILE EXT
                if (!Schema::hasColumn('file_uploads', 'file_ext')) {
                    Schema::table('file_uploads', function (Blueprint $table) {
                        $table->string('file_ext')->nullable();
                    });
                }
                //FILE IS DEFAULT
                if (!Schema::hasColumn('file_uploads', 'is_default')) {
                    Schema::table('file_uploads', function (Blueprint $table) {
                        $table->boolean('is_default')->nullable();
                    });
                }
                //LOCATION
                if (!Schema::hasColumn('file_uploads', 'location')) {
                    Schema::table('file_uploads', function (Blueprint $table) {
                        $table->text('location')->nullable();
                    });
                }
                //CREATED BY
                if (!Schema::hasColumn('file_uploads', 'created_by')) {
                    Schema::table('file_uploads', function (Blueprint $table) {
                        $table->text('created_by')->nullable();
                    });
                }
                //DELETED BY
                if (!Schema::hasColumn('file_uploads', 'deleted_by')) {
                    Schema::table('file_uploads', function (Blueprint $table) {
                        $table->text('deleted_by')->nullable();
                    });
                }

                return;
            } 



        Schema::create('file_uploads', function (Blueprint $table) {
            $table->id();
            $table->string('target_name');
            $table->string('target_id');
            $table->string('order_no');
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
