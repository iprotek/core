<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddMenuNotifier extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //  
        if(Schema::hasTable('accesses')) {
        
            DB::table('accesses')->insert(
                [ "id"=>19, "code"=>"manage-notify-scheduler", "name"=>"Notify Scheduler" ]
            ); 
            
            DB::select(" INSERT INTO `sys_sidemenu_items` (`id`,`ordinal`,`menu_text`,`url`,`icon`,`sidemenu_combo_id`,`sidemenu_group_id`,`control_access_id`,`created_at`,`updated_at`,`deleted_at`,`user_types`) VALUES (16,9,'Notify Scheduler','/manage/sys-notification/scheduler','fa-clock menu-sys-notification-scheduler',0,2,0,NULL,NULL,NULL,'0,1,2,3,4');");
        
        }
        else{

            DB::select(" INSERT INTO `sys_sidemenu_items` (`ordinal`,`menu_text`,`url`,`icon`,`sidemenu_combo_id`,`sidemenu_group_id`,`control_access_id`,`created_at`,`updated_at`,`deleted_at`,`user_types`) VALUES (9,'Notify Scheduler','/manage/sys-notification/scheduler','fa-clock menu-sys-notification-scheduler',0,2,0,NULL,NULL,NULL,'0,1,2,3,4');");
        
        }
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
