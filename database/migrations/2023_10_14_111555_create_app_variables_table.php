<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppVariablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        try{
           
            Schema::table('app_variables', function (Blueprint $table) {
                $table->string('name', 100);
                $table->longText('value')->nullable(); 
                $table->integer('updated_by')->nullable();
            });

            return;

        }catch(\Exception $ex){
            return;
        }

        Schema::create('app_variables', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->longText('value')->nullable(); 
            $table->integer('updated_by')->nullable();
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
        Schema::dropIfExists('app_variables');
    }
}
