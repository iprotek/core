<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        try{
            if(Schema::hasTable('categories')) {

                
                Schema::table('categories', function (Blueprint $table) {
                    $table->bigInteger('group_id');
                    $table->string('name');
                    $table->bigInteger('pay_created_by')->nullable(); 
                    $table->bigInteger('pay_updated_by')->nullable();
                    $table->bigInteger('pay_deleted_by')->nullable(); 
                });

                return;

            }
        }catch(\Exception $ex){
            return;
        }
        Schema::create('categories', function (Blueprint $table) { 
            $table->id();
            $table->bigInteger('group_id');
            $table->string('name');
            $table->bigInteger('pay_created_by')->nullable(); 
            $table->bigInteger('pay_updated_by')->nullable();
            $table->bigInteger('pay_deleted_by')->nullable(); 
            $table->timestamps();
            $table->softDeletes(); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
    }
}
