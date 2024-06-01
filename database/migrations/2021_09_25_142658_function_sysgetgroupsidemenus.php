<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class FunctionSysgetgroupsidemenus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        DB::unprepared("DROP FUNCTION IF EXISTS fnSysGetGroupSidemenus");

        DB::unprepared("
CREATE  FUNCTION `fnSysGetGroupSidemenus`(_UserID INT, _RoleID VARCHAR(20)) RETURNS longtext CHARSET utf8mb4
BEGIN
    DECLARE _RESULT LONGTEXT;
    SET SESSION group_concat_max_len = 1000000;
    
    SELECT 
        CONCAT('[',
            GROUP_CONCAT( JSON_OBJECT( 'id', id, 'user_types', user_types, 'group_text', group_text,'items', JSON_EXTRACT( fnSysGetSidemenusComboItems(_UserID, _RoleID, id),'$'  ) ) order by ordinal),
            ']') INTO _RESULT
    FROM 
        sys_sidemenu_groups ;
        
    #APPEND OTHERS
    SET _RESULT = JSON_ARRAY_APPEND(_RESULT,'$', JSON_OBJECT( 'id', 0, 'user_types', null, 'group_text', 'OTHERS','items', JSON_EXTRACT( fnSysGetSidemenusComboItems(_UserID, _RoleID, 0),'$'  ) ));
    
RETURN _RESULT;
END
        ");


        DB::table('sys_sidemenu_groups')->insert([
            'ordinal' => 1000,
            'group_text' => 'SYSTEM'
        ]);

        DB::table('sys_sidemenu_groups')->insert([
            'ordinal' => 1,
            'group_text' => 'MAIN MENU'
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
