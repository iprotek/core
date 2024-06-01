<?php
namespace App\Helpers;

use DB; 
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use App\Helpers\PayModelHelper;
use App\Models\Branch;
use App\Models\SharedAccountDefaultBranch;
use App\Models\SharedAccountAllowedBranch;

class BranchHelper
{ 
    public static function branch_constraint($model, Request $request, $column_key = 'id'){
        $column_key = $column_key ?:"id";
        $user = $request->get('user');
        $my_id = $user['app_user_account']['id'];
        $proxy_group_id = $user['group_id'];
        //Log::error("MYID: ".$my_id." GROUP ID".$proxy_group_id);
        $model->whereRaw(" ( group_id = ? OR ( ".$column_key." = 0 AND pay_created_by = ? ) OR ".$column_key." IN ( SELECT branch_id FROM shared_account_allowed_branches WHERE group_id = ? AND shared_account_id = ? AND is_allowed = 1 ) ) ", [ $my_id, $my_id, $proxy_group_id, $my_id ]);
        return $model;
    }

    
    public static function getConstraintBranch(Request $request, $fields=[]){
        $branches = PayModelHelper::get( Branch::class, $request, $fields);
        
        static::branch_constraint($branches, $request, "id");
        //$m = $this->getOwn($model, $request, $fields);
        $branch = $branches->first();
        if($branch){
            return $branch;
        }
        response()->json(["message"=>"Branch not allowed."], 403)->send();
        die(); 
    }

    public static function getDefaultBranch(Request $request, $get_first_if_empty=true){
        $user = $request->get('user');
        $my_id = $user['app_user_account']['id'];

        //Check if you  have set the default branch
        $default_branch = PayModelHelper::get(SharedAccountDefaultBranch::class, $request, ["shared_account_id"=>$my_id] )->first();
        if($default_branch){
            $branch_id = $default_branch->branch_id;

            //Check if you are allowed to access the branch
            $branches = PayModelHelper::get( Branch::class, $request, ["id"=>$branch_id]);
            static::branch_constraint($branches, $request, "id");
            $branch = $branches->first();
            if($branch){
                return ["branch"=>$branch];
            }
            //return "C";
        }
        if($get_first_if_empty !== true) return response()->json(null);
 
        //Check if you have 
        //return $my_id;
        $allowed_branch = PayModelHelper::get(SharedAccountAllowedBranch::class, $request)
            ->whereRaw(' ( group_id  = ? OR ( shared_account_id = ? AND is_allowed = 1 ) ) ', [$my_id, $my_id])->first(); 
        //, ["shared_account_id"=>$my_id, "is_allowed"=>1])->first();
        if($allowed_branch){
            $branch_id = $allowed_branch->branch_id;

            $branches = PayModelHelper::get( Branch::class, $request, ["id"=>$branch_id]);
            static::branch_constraint($branches, $request, "id");
            $branch = $branches->first();

            return ["branch"=>$branch];

        }

        return response()->json(null);//->send();;
    }

    public static function setDefaultBranch(Request $request){
        $user = $request->get('user');
        $my_id = $user['app_user_account']['id'];
        $branch_id = $request->branch_id <= 0 ? 0 : $request->branch_id;

        if($branch_id * 1 > 0){

            //Check if allowed in branch.
            static::getConstraintBranch($request,["id"=>$branch_id]);

            $default_branch = PayModelHelper::get( SharedAccountDefaultBranch::class, $request,["shared_account_id"=>$my_id])->first();
            if($default_branch){
                PayModelHelper::update( $default_branch, $request, ["branch_id"=>$branch_id]);
            }else{
                PayModelHelper::create( SharedAccountDefaultBranch::class, $request, [ "shared_account_id"=>$my_id, "branch_id"=>$branch_id]);
            }

            return ["status"=>1, "message"=>"Set branch default"];
        }else{
            
            $default_branch = PayModelHelper::get( SharedAccountDefaultBranch::class, $request,["shared_account_id"=>$my_id])->first();
            if($default_branch){
                $default_branch->delete();
            }
            
            return ["status"=>1, "message"=>"Cleared branch default"];
        }
        return ["status"=>0, "message"=>"Failed to set default"];

    }

}