<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFileImportBatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('file_import_batches', function (Blueprint $table) {
            $table->id();
            
            $table->bigInteger('group_id')->nullable();
            $table->bigInteger('pay_created_by')->nullable();
            $table->bigInteger('pay_updated_by')->nullable();
            $table->bigInteger('pay_deleted_by')->nullable();

            $table->string('file_name');
            $table->string('file_ext');
            $table->string('target_field');
            $table->text('settings'); //JSON SETTINGS LIKE BRANCH etch.
            $table->integer('total_lines'); //LINES READ ON CSV OR JSON WHILE PROCESSING
            $table->integer('line_processing')->default(0);
            $table->integer('line_succeed')->default(0);
            $table->integer('line_failed')->default(0);
            $table->integer('line_valid')->default(0);
            //0-Pending, 1-Completed, 2-Failed, 3-Processing, 4-Stopped, 5-Reset, 6-Restart
            $table->integer('status_id')->default(0);
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
        Schema::dropIfExists('file_import_batches');
    }
}
