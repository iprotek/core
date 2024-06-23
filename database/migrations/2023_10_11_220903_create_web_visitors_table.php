<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWebVisitorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        try{
           
            Schema::table('web_visitors', function (Blueprint $table) {
                $table->string('ip_address');
                $table->longText('user_agent');
            });

            return;

        }catch(\Exception $ex){
            return;
        }

        
        Schema::create('web_visitors', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address');
            $table->longText('user_agent');
            //$table->dateTime('visited_at');
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
        Schema::dropIfExists('web_visitors');
    }
}
