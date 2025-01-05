<?php

namespace iProtek\Core\Helpers;
use Illuminate\Support\Facades\Session;
use App\Models\UserAdminCompany;
use DB;
use iProtek\Core\Models\Branch;

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
            }else{
                

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

        $branches = Branch::on();
        $branches->where('is_active', 1);
        return $branches->get();
    }
}
