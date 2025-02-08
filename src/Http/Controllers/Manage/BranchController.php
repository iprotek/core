<?php

namespace iProtek\Core\Http\Controllers\Manage;

use Illuminate\Http\Request;
use iProtek\Core\Http\Controllers\_Common\_CommonOwnGroupController;
use iProtek\Core\Models\Branch;
use iProtek\Core\Helpers\PayModelHelper;
use Illuminate\Support\Facades\Log;
use iProtek\Core\Helpers\BranchHelper;

class BranchController extends _CommonOwnGroupController
{
    //
    public $guard = 'admin';
    
    public function index(Request $request){
        return $this->view('manage.branch');
    }

    public function own_list(Request $request){ 
        $result = $this->getOwn( Branch::class, $request);
        $search_text = str_replace(' ','%', $request->search?:"");
        $result->whereRaw(" name LIKE CONCAT('%',?,'%')",[$search_text])->orderBy('name','ASC');
        return $result->paginate(10);

    }

    public function list(Request $request){

        //$user = $request->get('user');
        //$my_id = $user['app_user_account']['id'];
        //$proxy_group_id = $user['group_id'];
        
        $result = PayModelHelper::get(Branch::class, $request);
        $search_text = str_replace(' ','%', $request->search_text?:"");
        //Search
        $result->whereRaw(" name LIKE CONCAT('%',?,'%')",[$search_text]);
        

        //Constraint
        BranchHelper::branch_constraint($result, $request);
        
        $result->select('id','name as text');
        $result->orderBy('name','ASC');

        if($request->is_all == "yes"){
            $request->where('is_active', 1);
            return $result->get();
        }


        return $result->paginate(10);

    }

    public function add_own_branch(Request $request){

        $this->validate($request, [
            "name"=>"required",
            "address"=>"required",
            "coordinates" => "required"
        ]);

        $exists = PayModelHelper::get_own(Branch::class, $request, ["address"=>$request->name])->first();
        if($exists){
            return ["status"=>0, "message"=>"Branch name already exists."];
        }
        
        $branch = PayModelHelper::create_own(Branch::class, $request, [
            "name"=>$request->name,
            "address"=>$request->address,
            "coordinates"=>$request->coordinates,
            "status"=>$request->status,
            "status_info"=>$request->status_info
        ]);        
        //Create HERE
        return ["status"=>1, "message"=>"Successfully Added.", "data"=>$branch];

    }

    public function update_own_branch(Request $request){

        $this->validate($request, [
            "billing_branch_id"=>"required",
            "name"=>"required",
            "address"=>"required",
            "coordinates" => "required"
        ]);
        
        //Ensure Valid Account
        $billing_branch_id = $request->billing_branch_id;
        $billing_branch = $this->getValidOwn(Branch::class, $request, ["id"=>$billing_branch_id]); //PayModelHelper::get_own(Branch::class, $request, ["id"=>$billing_branch_id])->first();
 
        $billing_group = PayModelHelper::get_own(Branch::class, $request, ["name"=>$request->name])->whereRaw(" id <> ? ",[$billing_branch->id])->first();
        if($billing_group){
            return ["status"=>0, "message"=>"Branch name already Exists"];
        } 
        
        PayModelHelper::update_own(
            $billing_branch,
            $request, [ 
                "name"=>$request->name,
                "address"=>$request->address,
                "coordinates"=>$request->coordinates,
                "status"=>$request->status,
                "status_info"=>$request->status_info
            ]
        );
        return ["status"=>1, "message"=>"Successfully Updated.", "data"=>$billing_branch];

    }
 
    public function delete_own_branch(Request $request){     
        $id = $request->id;
        $billing_branch = $this->getValidOwn(Branch::class, $request, ["id"=>$id]); 
         
        if(!$billing_branch){
            return ["status"=>0, "message"=>"Not Found"];
        }    
        PayModelHelper::delete($billing_branch, $request);
        
        return ["status"=>1, "message"=>"Successfully removed."];     
    }
    
}
