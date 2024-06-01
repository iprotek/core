<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class FunctionSysgetsidemenuitems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        DB::unprepared("DROP FUNCTION IF EXISTS fnSysGetSidemenuItems");
        DB::unprepared("
CREATE FUNCTION `fnSysGetSidemenuItems`(_UserID INT, 
_RoleID VARCHAR(20),
_SideMenuComboID INT
) RETURNS longtext CHARSET utf8mb4
BEGIN
    DECLARE _RESULT LONGTEXT;
    
    SELECT CONCAT('[', GROUP_CONCAT(
        JSON_OBJECT(
            'id', id,
            'ordinal', ordinal,
            'menu_text', menu_text,
            'url', url,
            'icon', icon
        )
        order by ordinal, menu_text
    ),']') INTO _RESULT 
    FROM 
        sys_sidemenu_items WHERE sidemenu_combo_id = _SideMenuComboID;
    
    
    
RETURN _RESULT;
END
        ");


        DB::table('sys_sidemenu_items')->insert([
            'ordinal' => 1,
            'menu_text' => 'Sidemenus',
            'url' => '/admin/sidemenus',
            'icon' => 'fa-save',
            'sidemenu_combo_id' =>1,
            'control_access_id' =>0, 
            'sidemenu_group_id'=>1
        ]);
        DB::table('sys_sidemenu_items')->insert([
            'ordinal' => 1,
            'menu_text' => 'Admin Accounts',
            'url' => '/admin/accounts',
            'icon' => 'fa-save',
            'sidemenu_combo_id' =>0,
            'control_access_id' =>0, 
            'sidemenu_group_id'=>1
        ]);
        DB::table('sys_sidemenu_items')->insert([
            'ordinal' => 2,
            'menu_text' => 'Customer Accounts',
            'url' => '/admin/customer-accounts',
            'icon' => 'fa-save',
            'sidemenu_combo_id' =>0,
            'control_access_id' =>0, 
            'sidemenu_group_id'=>1
        ]);
        DB::table('sys_sidemenu_items')->insert([
            'ordinal' => 3,
            'menu_text' => 'Vendor Accounts',
            'url' => '/admin/vendor-accounts',
            'icon' => 'fa-save',
            'sidemenu_combo_id' =>0,
            'control_access_id' =>0, 
            'sidemenu_group_id'=>1
        ]);
        DB::table('sys_sidemenu_items')->insert([
            'ordinal' => 1,
            'menu_text' => 'Dashboard',
            'url' => '/admin',
            'icon' => 'fa-tachometer-alt',
            'sidemenu_combo_id' =>0,
            'control_access_id' =>0, 
            'sidemenu_group_id'=>2
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
