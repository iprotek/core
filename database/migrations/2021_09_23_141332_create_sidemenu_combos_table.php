<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSidemenuCombosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('sys_sidemenu_combos')) {

            // ORDINAL
            if (!Schema::hasColumn('sys_sidemenu_combos', 'ordinal')) {
                Schema::table('sys_sidemenu_combos', function (Blueprint $table) {
                    $table->integer('ordinal')->nullable();
                });
            }
            // GROUP TEXT
            if (!Schema::hasColumn('sys_sidemenu_combos', 'group_text')) {
                Schema::table('sys_sidemenu_combos', function (Blueprint $table) {
                    $table->string('group_text')->nullable();
                });
            }
            // ICON
            if (!Schema::hasColumn('sys_sidemenu_combos', 'icon')) {
                Schema::table('sys_sidemenu_combos', function (Blueprint $table) {
                    $table->string('icon')->nullable();
                });
            }
            // GROUP_ID
            if (!Schema::hasColumn('sys_sidemenu_combos', 'sidemenu_group_id')) {
                Schema::table('sys_sidemenu_combos', function (Blueprint $table) {
                    $table->integer('sidemenu_group_id');
                });
            }
            //SOFT DELETES
            if (!Schema::hasColumn('sys_sidemenu_combos', 'deleted_at')) {
                Schema::table('sys_sidemenu_combos', function (Blueprint $table) {
                    $table->softDeletes();
                });
            }

            return;
        }

        Schema::create('sys_sidemenu_combos', function (Blueprint $table) {
            $table->id();
            $table->integer('ordinal');
            $table->string('combo_text')->nullable();
            $table->string('icon');
            $table->integer('sidemenu_group_id');
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
        Schema::dropIfExists('sys_sidemenu_combos');
    }
}
