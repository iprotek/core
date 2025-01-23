<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFileImportDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('file_import_data', function (Blueprint $table) {
            $table->id();
            
            $table->bigInteger('group_id')->nullable();
            $table->bigInteger('pay_created_by')->nullable();
            $table->bigInteger('pay_updated_by')->nullable();
            $table->bigInteger('pay_deleted_by')->nullable();

            $table->longText('json_data');
            $table->integer('file_import_batch_id');
            $table->integer('status_id')->default(0); //0-Pending, 1-Success, 2-Failed
            $table->longText('status_info')->nullable();
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
        Schema::dropIfExists('file_import_data');
    }
}
