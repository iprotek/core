<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuperAdminSubAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        try{
           
            Schema::table('super_admin_sub_accounts', function (Blueprint $table) {
                $table->string('email', 50)->unique();
                $table->string('user_type'); //1-Admin //2Sub Account
                $table->bigInteger('sub_account_group_id'); //
            });

            return;

        }catch(\Exception $ex){
            return;
        }
        Schema::create('super_admin_sub_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('email', 50)->unique();
            $table->string('user_type'); //1-Admin //2Sub Account
            $table->bigInteger('sub_account_group_id'); //
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
        Schema::dropIfExists('super_admin_sub_accounts');
    }
}
