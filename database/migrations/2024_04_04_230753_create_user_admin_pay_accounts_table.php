<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserAdminPayAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        try{
           
            Schema::table('user_admin_pay_accounts', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->integer('user_admin_id')->unique();
                $table->bigInteger('default_proxy_group_id');
                $table->bigInteger('own_proxy_group_id');
                $table->bigInteger('pay_app_user_account_id');
                $table->string('email');
                $table->text('access_token');
                $table->text('refresh_token');
                $table->bigInteger('sub_account_group_id')->nullable(); 
            });

            return;

        }catch(\Exception $ex){
            return;
        }

        Schema::create('user_admin_pay_accounts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_admin_id')->unique();
            $table->bigInteger('default_proxy_group_id');
            $table->bigInteger('own_proxy_group_id');
            $table->bigInteger('pay_app_user_account_id');
            $table->string('email');
            $table->text('access_token');
            $table->text('refresh_token');
            $table->bigInteger('sub_account_group_id')->nullable();
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
        Schema::dropIfExists('user_admin_pay_accounts');
    }
}
