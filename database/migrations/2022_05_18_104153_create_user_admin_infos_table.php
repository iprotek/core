<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserAdminInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('user_admin_infos')) { 
 
            return;
            
        }
        Schema::create('user_admin_infos', function (Blueprint $table) {
            $table->id();
            $table->string('user_admin_id');
            $table->string('company_id');
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('position');
            $table->string('department');
            $table->string('line');
            $table->string('factory');
            $table->integer('is_active');
            $table->integer('status_id');
            $table->string('region');
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
        Schema::dropIfExists('user_admin_infos');
    }
}
