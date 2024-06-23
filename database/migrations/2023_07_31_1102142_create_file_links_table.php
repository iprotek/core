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

            //NAME
            if (!Schema::hasColumn('file_links', 'name')) {
                Schema::table('file_uploads', function (Blueprint $table) {
                    $table->string('name')->nullable();
                });
            }
            //URL
            if (!Schema::hasColumn('file_links', 'url')) {
                Schema::table('file_uploads', function (Blueprint $table) {
                    $table->text('url')->nullable();
                });
            }
            //FILE TYPE
            if (!Schema::hasColumn('file_links', 'file_type')) {
                Schema::table('file_uploads', function (Blueprint $table) {
                    $table->string('file_type')->nullable();
                });
            }
            //CREATED BY
            if (!Schema::hasColumn('file_links', 'created_by')) {
                Schema::table('file_uploads', function (Blueprint $table) {
                    $table->integer('created_by')->nullable();
                });
            }
            //UPDATED BY
            if (!Schema::hasColumn('file_links', 'updated_by')) {
                Schema::table('file_uploads', function (Blueprint $table) {
                    $table->integer('updated_by')->nullable();
                });
            }
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
