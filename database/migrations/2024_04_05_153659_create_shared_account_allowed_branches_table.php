<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSharedAccountAllowedBranchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    { 
        if(Schema::hasTable('shared_account_allowed_branches')) { 
            return;
        }
 
        Schema::create('shared_account_allowed_branches', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('group_id');
            $table->integer('branch_id');
            $table->bigInteger('shared_account_id')->nullable();
            $table->boolean('is_allowed')->nullable();
            $table->bigInteger('pay_created_by')->nullable(); 
            $table->bigInteger('pay_updated_by')->nullable();
            $table->bigInteger('pay_deleted_by')->nullable(); 
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
        Schema::dropIfExists('shared_account_allowed_branches');
    }
}
