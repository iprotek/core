<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FnComboMenuUpdate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
    
        if(Schema::hasTable('sys_sidemenu_items')) { 
            
            if (!Schema::hasColumn('sys_sidemenu_items', 'menu_control_name')) {
                Schema::table('sys_sidemenu_items', function (Blueprint $table) {
                    $table->string('menu_control_name')->nullable();
                });
            }
        } 


        
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
            'icon', icon,
            'menu_control_name', menu_control_name
        )
        order by ordinal, menu_text
    ),']') INTO _RESULT 
    FROM 
        sys_sidemenu_items WHERE sidemenu_combo_id = _SideMenuComboID;
    
    
    
RETURN _RESULT;
END
        ");



        DB::unprepared("DROP FUNCTION IF EXISTS fnSysGetSidemenusComboItems");
        DB::unprepared("
CREATE FUNCTION `fnSysGetSidemenusComboItems`(_UserID INT, 
_RoleID VARCHAR(20),
_SideMenuGroupID INT
) RETURNS longtext CHARSET utf8mb4
BEGIN
    DECLARE _RESULT LONGTEXT;
    
    #COMBO ITEM
    SELECT CONCAT('[', GROUP_CONCAT(
        JSON_OBJECT(
            'id', id,
            'ordinal', ordinal,
            'menu_text', menu_text,
            'type', `type`,
            'items', JSON_EXTRACT( items, '$'),
            'url', url,
            'icon', icon,
            'user_types', user_types,
            'menu_control_name', menu_control_name
        )  order by ordinal, menu_text
    ), ']') INTO _RESULT FROM (
    SELECT 
        id, ordinal, combo_text as menu_text , 'combo' as `type`, fnSysGetSidemenuItems(_UserID, _RoleID, id) as items, '#' as url, icon, '' as user_types, '' as menu_control_name
    FROM sys_sidemenu_combos WHERE sidemenu_group_id =  _SideMenuGroupID UNION
    SELECT
        id, ordinal, menu_text, 'menu' as `type`, null as items, url, icon, user_types, menu_control_name
    FROM sys_sidemenu_items WHERE sidemenu_group_id = _SideMenuGroupID AND ( sidemenu_combo_id = 0 OR sidemenu_combo_id IS NULL)
    ) as MergedData order by ordinal;
    
RETURN _RESULT;
END

        ");
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
