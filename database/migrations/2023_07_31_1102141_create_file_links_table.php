<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFileLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('file_links')) {

            Schema::table('file_links', function (Blueprint $table) {
                $table->string('name')->nullable();
                $table->text('url')->nullable();
                $table->string('file_type')->nullable();
                $table->integer('created_by')->nullable();
                $table->integer('updated_by')->nullable();
            });

            return;

        }
        Schema::create('file_links', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('url');
            $table->string('file_type');
            $table->integer('created_by');
            $table->integer('updated_by')->nullable();
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
        Schema::dropIfExists('file_links');
    }
}
