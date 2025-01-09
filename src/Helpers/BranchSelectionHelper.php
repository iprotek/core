<?php

namespace iProtek\Core\Helpers;
use Illuminate\Support\Facades\Session;
use App\Models\UserAdminCompany;
use DB;
use iProtek\Core\Models\Branch;
use iProtek\Xrac\Models\XuserRole;

class BranchSelectionHelper
{
 

    public static function get(){
        
        if(static::disable_multi_branch()){
            return 1;
        }


       $user = auth()->user();
       //return $suggestions;
       $branch_id = Session::get('branch-selection-id-'.$user->id);
       
       if(!$branch_id){
            //GET ALL Branches that is active
            if( $user->can('super-admin') ){
                //$companyDetail =  CompanyDetail::first();
                $branches = static::active_branches();


            }else{
                

            }
            $branches = static::active_branches();
            if( count($branches) > 0){
                static::set($branches[0]['id']);
                return $branches[0]['id'];
            }
            //GET 1 as default
            return 1;
        }
        else{
            if(  $user->can('super-admin') ){
            } 
        }
        return $branch_id;

    }

    private static function get_user_default($user){
        
        if(static::disable_multi_branch()){
            return 1;
        }
        if(  $user->can('super-admin') ){

        }
        //return static::get();
    }

    public static function disable_multi_branch(){
    
        return config('iprotek.disable_multi_branch') &&  !in_array( strtolower( config('iprotek.disable_multi_branch') ), [ 'false', 'no'] );
    }

    public static function set( $id){

        if(static::disable_multi_branch()){
            return 1;
        }

        $user = auth()->user();
        if( $user->can('super-admin') ){
            Session::put('branch-selection-id-'.$user->id, $id );
        } 
        Session::put('branch-selection-id-'.$user->id, $id );
        return null; 

    }

    public static function active_branches(){

        if(static::disable_multi_branch()){
            return [
                "id"=>1,
                "name"=>"DEFAULT COMPANY/BRANCH",
                "is_active"=>true
            ];
        }

        $branchList = Branch::on();
        $branchList->where('is_active', 1);
        $branches = $branchList->get();
        if(count($branches) <= 0){
            return [
                "id"=>1,
                "name"=>"DEFAULT COMPANY/BRANCH",
                "is_active"=>true
            ];
        }

        
        $user = auth()->user();
        if($user->id != 1){
            //FILTER BRANCHES BASED ON ALLOWED ACCESS
            $allowedBranches = XuserRole::where([
                "app_account_id"=>PayHttp::pay_account_id(),
                "is_allowed"=>true
            ])->get();
            //GETTING IDS
            $allowBranchIds=[];
            foreach($allowedBranches as $allow){
                $allowBranchIds[] = $allow->branch_id;
            }
            
            $branches = $branches->filter( function($a)use($allowBranchIds){
                return in_array($a->id, $allowBranchIds);
            });

        }
        return $branches;
    }
}
