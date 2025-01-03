<?php

namespace iProtek\Core\Helpers;
use Illuminate\Support\Facades\Session;
use App\Models\UserAdminCompany;
use DB;

class BranchSelectionHelper
{
 

    public static function get(){
        
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
        
        if(  $user->can('super-admin') ){

        }
        //return static::get();
    }

    public static function set( $id){

        $user = auth()->user();
        if( $user->can('super-admin') ){
            Session::put('branch-selection-id-'.$user->id, $id );
        } 
        Session::put('branch-selection-id-'.$user->id, $id );
        return null; 

    }
}
