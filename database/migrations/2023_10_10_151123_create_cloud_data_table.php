<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCloudDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
 
        if(Schema::hasTable('cloud_data')) {
            return;
        }


        Schema::create('cloud_data', function (Blueprint $table) {
            $table->id();
            $table->string('service_name');
            $table->integer('file_upload_id');
            $table->string('file_allocation');
            $table->string('file_name');
            $table->boolean('is_uploaded');
            $table->longText('backup_infos')->nullable();
            $table->longText('error_infos')->nullable();
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
        Schema::dropIfExists('cloud_data');
    }
}
