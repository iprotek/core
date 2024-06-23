<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSharedAccountDefaultBranchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        try{
           
            Schema::table('shared_account_allowed_branches', function (Blueprint $table) {
                $table->bigInteger('group_id');
                $table->bigInteger('shared_account_id');
                $table->bigInteger('branch_id');
                $table->bigInteger('pay_created_by')->nullable();
                $table->bigInteger('pay_updated_by')->nullable();
                $table->bigInteger('pay_deleted_by')->nullable();
            });

            return;

        }catch(\Exception $ex){
            return;
        }
        Schema::create('shared_account_default_branches', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('group_id');
            $table->bigInteger('shared_account_id');
            $table->bigInteger('branch_id');
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
        Schema::dropIfExists('shared_account_default_branches');
    }
}
