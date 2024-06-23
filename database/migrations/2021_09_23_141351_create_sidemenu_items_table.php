<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSidemenuItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('sys_sidemenu_items')) {

            // ORDINAL
            if (!Schema::hasColumn('sys_sidemenu_items', 'ordinal')) {
                Schema::table('sys_sidemenu_items', function (Blueprint $table) {
                    $table->integer('ordinal')->nullable();
                });
            } 
            if (!Schema::hasColumn('sys_sidemenu_items', 'menu_text')) {
                Schema::table('sys_sidemenu_items', function (Blueprint $table) {
                    $table->string('menu_text')->nullable();
                });
            }
            if (!Schema::hasColumn('sys_sidemenu_items', 'url')) {
                Schema::table('sys_sidemenu_items', function (Blueprint $table) {
                    $table->text('url')->nullable();
                });
            }
            if (!Schema::hasColumn('sys_sidemenu_items', 'icon')) {
                Schema::table('sys_sidemenu_items', function (Blueprint $table) {
                    $table->string('icon')->nullable();
                });
            } 
            if (!Schema::hasColumn('sys_sidemenu_items', 'sidemenu_combo_id')) {
                Schema::table('sys_sidemenu_items', function (Blueprint $table) {
                    $table->integer('sidemenu_combo_id')->nullable();
                });
            }
            if (!Schema::hasColumn('sys_sidemenu_items', 'sidemenu_group_id')) {
                Schema::table('sys_sidemenu_items', function (Blueprint $table) {
                    $table->integer('sidemenu_group_id')->nullable();
                });
            }
            if (!Schema::hasColumn('sys_sidemenu_items', 'control_access_id')) {
                Schema::table('sys_sidemenu_items', function (Blueprint $table) {
                    $table->integer('sidemenu_group_id')->nullable();
                });
            }
            if (!Schema::hasColumn('sys_sidemenu_items', 'deleted_at')) {
                Schema::table('sys_sidemenu_items', function (Blueprint $table) {
                    $table->softDeletes();
                });
            }
            if (!Schema::hasColumn('sys_sidemenu_items', 'user_types')) {
                Schema::table('sys_sidemenu_items', function (Blueprint $table) {
                    $table->string('user_types')->nullable();
                });
            }

            return;
        }

        Schema::create('sys_sidemenu_items', function (Blueprint $table) {
            $table->id();
            $table->integer('ordinal');
            $table->string('menu_text');
            $table->text('url');
            $table->string('icon');
            $table->integer('sidemenu_combo_id');
            $table->integer('sidemenu_group_id');
            $table->integer('control_access_id');
            $table->timestamps();
            $table->softDeletes();
            $table->string('user_types')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sys_sidemenu_items');
    }
}
