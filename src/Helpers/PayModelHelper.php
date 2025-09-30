<?php
namespace iProtek\Core\Helpers;

use DB; 
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session; 
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class PayModelHelper
{ 
    public static function get_group_id($user, $is_own){
        $group_id = null;
        if($is_own === false){
            $group_id = $user['group_id'];
        }
        else{            
            $group_id = $user['app_user_account']['id'];
            //$group_id = $own_group['group_id'];
        }
        return $group_id;
    }
    public static function get_user_id($user){
        return $user['app_user_account']['id'];
    }

    public static function create($class, Request $request, $fields, $is_own=false){
        $user = $request->get('user'); 
        $fields['group_id'] = static::get_group_id($user, $is_own);
        $fields['pay_created_by'] = static::get_user_id($user);

        //FIXING instead using of ::create
        $new_class = (new $class);
        $new_class->fill( $fields );
        $new_class->save();        

        return $new_class;
    }
    public static function create_own($class, Request $request, $fields=[]){
        return static::create($class, $request, $fields, true);
    }

    public static function update($class, Request $request, $fields, $is_own=false){
        $user = $request->get('user');
        if(Schema::hasColumn( $class->getTable(), 'group_id' )){
            $fields['group_id'] = static::get_group_id($user, $is_own);
        }
        $class->fill($fields);
        if($class->isDirty()){
            $fields['pay_updated_by'] = static::get_user_id($user);
            return $class->update($fields);
        }
        return $class;
    }
    public static function update_own($class, Request $request, $fields ){
        return static::update($class, $request, $fields, true);
    }

    public static function get($class, Request $request, $fields=[], $is_own=false){
        $user = $request->get('user'); 
        if(is_string($class)){
            $class = new $class;
        }
        if(Schema::hasColumn( $class->getTable(), 'group_id' )){
            $fields['group_id'] = static::get_group_id($user, $is_own);
        }
        return $class::where($fields);
    }
    public static function get_own($class, Request $request, $fields=[] ){
        return static::get($class, $request, $fields, true);
    }    

    public static function delete($class, $request){
        $user = $request->get('user');        
        $user_id = $user['app_user_account']['id'];
        if(Schema::hasColumn( $class->getTable(), 'pay_deleted_by' )){
            $class['pay_deleted_by'] = $user_id;
            $class->save();
        }
        $class->delete();

    }

}