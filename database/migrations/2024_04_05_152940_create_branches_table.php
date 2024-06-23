<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBranchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    { 
        if(Schema::hasTable('branches')) { 
            return;
        }
 
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('group_id');
            $table->string('name');
            $table->text('address')->nullable();
            $table->text('coordinates')->nullable();
            $table->string('status')->nullable();
            $table->text('status_info')->nullable();
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
        Schema::dropIfExists('branches');
    }
}
