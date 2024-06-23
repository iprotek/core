<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('user_admins')) { 
            if (!Schema::hasColumn('user_admins', 'user_type')) {
                Schema::table('user_admins', function (Blueprint $table) {
                    $table->integer('user_type')->nullable();
                });
            }

            return;
            
        }
        Schema::create('user_admins', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('username', 50)->unique();
            $table->string('company_id', 50)->unique();
            $table->string('email');//->unique();
            $table->string('contact_no')->nullable();
            $table->integer('user_type')->nullable(); //0/null - admin, 1 - requestor, 2 - fie/cie, 3 - HR, 4 - DEPT HEAD
            $table->integer('is_verified');
            $table->string('region')->nullable();
            $table->integer('nopass')->nullable();
            $table->string('password');
            $table->boolean('can_classify')->nullable();
            $table->boolean('can_evaluate')->nullable();
            $table->boolean('can_approve')->nullable();
            $table->boolean('can_implement')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->integer('deleted_by')->nullable();
            $table->boolean('is_protected')->nullable();
            $table->integer('recovery_requested')->nullable();
            $table->dateTime('recovery_requested_at')->nullable();
            $table->text('recovery_requested_reason')->nullable();
            $table->string('lang')->nullable();
            $table->string('remember_token')->nullable();
            $table->dateTime('email_verified_at')->nullable();
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
        Schema::dropIfExists('user_admins');
    }
}
