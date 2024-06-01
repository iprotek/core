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
