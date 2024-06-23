<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSidemenuGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('sys_sidemenu_groups')) {

            // ORDINAL
            if (!Schema::hasColumn('sys_sidemenu_groups', 'ordinal')) {
                Schema::table('sys_sidemenu_groups', function (Blueprint $table) {
                    $table->integer('ordinal')->nullable();
                });
            }
            // GROUP TEXT
            if (!Schema::hasColumn('sys_sidemenu_groups', 'group_text')) {
                Schema::table('sys_sidemenu_groups', function (Blueprint $table) {
                    $table->string('group_text')->nullable();
                });
            }
            // GROUP TEXT
            if (!Schema::hasColumn('sys_sidemenu_groups', 'user_types')) {
                Schema::table('sys_sidemenu_groups', function (Blueprint $table) {
                    $table->string('user_types')->nullable();
                });
            }
            // GROUP TEXT
            if (!Schema::hasColumn('sys_sidemenu_groups', 'deleted_at')) {
                Schema::table('sys_sidemenu_groups', function (Blueprint $table) {
                    $table->softDeletes();
                });
            }

            return;
        }
        Schema::create('sys_sidemenu_groups', function (Blueprint $table) {
            $table->id();
            $table->integer('ordinal');
            $table->string('group_text');
            $table->string('user_types')->nullable();
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
        Schema::dropIfExists('sys_sidemenu_groups');
    }
}
