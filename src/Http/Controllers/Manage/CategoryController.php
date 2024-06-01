<?php

namespace iProtek\Core\Http\Controllers\Manage;

use Illuminate\Http\Request;
use iProtek\Core\Http\Controllers\_Common\_CommonController;
use iProtek\Core\Models\Category;
use iProtek\Core\Helpers\PayModelHelper;

class CategoryController extends _CommonController
{
    //
    public $guard = 'admin';

    public function list(Request $request){
                
        $groups = PayModelHelper::get(Category::class, $request);//BillingAccount::on(); 
        $search_text = str_replace(' ','%', $request->search?:"");
        $groups->whereRaw(" name LIKE CONCAT('%',?,'%')",[$search_text])->orderBy('name','ASC');
        return $groups->paginate(10);

    }

    public function list_selection(Request $request){
        $groups = PayModelHelper::get(Category::class, $request);//BillingAccount::on(); 
        $search_text = str_replace(' ','%', $request->search?:"");
        $groups->whereRaw(" name LIKE CONCAT('%',?,'%')",[$search_text]);
        $groups->select('id', 'name as text');
        $groups->orderBy('name','ASC');
        return $groups->paginate(10);
    }

    public function add_group(Request $request){
        
        $this->validate($request, [
            "name"=>"required"
        ]);

        $billing_group = PayModelHelper::get(Category::class, $request, ["name"=>$request->name])->first();
        if($billing_group){
            return ["status"=>0, "message"=>"Name already Exists"];
        }
        
        $billing_group = PayModelHelper::create(Category::class, $request, [
            "name"=>$request->name
        ]);
        
        //Create HERE
        return ["status"=>1, "message"=>"Successfully Added.", "data"=>$billing_group];

    }

    public function update_group(Request $request){

        $this->validate($request, [
            "name"=>"required"
        ]);

        $billing_category_id = $request->billing_category_id;

        $billing_group = PayModelHelper::get(Category::class, $request, ["name"=>$request->name])->whereRaw(" id <> ? ",[$billing_category_id])->first();
        if($billing_group){
            return ["status"=>0, "message"=>"Name already Exists"];
        }
        $billing_group = Category::find($billing_category_id);
        
        PayModelHelper::update(
            $billing_group,
            $request, [ 
                "name"=>$request->name 
            ]
        );
        return ["status"=>1, "message"=>"Successfully Updated.", "data"=>$billing_group];

    }
    
    public function get_group(Request $request){
        $id = $request->id;
        $app_user_account = PayModelHelper::get(Category::class, $request,["id"=>$id])->first(); 
        return $app_user_account; 
    }
    
    public function delete_group(Request $request){     
        $app_user_account = PayModelHelper::get(Category::class, $request, ["id"=>$request->id])->first(); 
        if(!$app_user_account){
            return ["status"=>0, "message"=>"Not Found".$id];
        }    
        PayModelHelper::delete($app_user_account, $request);
        
        return ["status"=>1, "message"=>"Successfully removed."];     
    }


}
