<?php

namespace iProtek\Core\Http\Controllers\Manage;

use Illuminate\Http\Request;
use iProtek\Core\Models\SharedAccountAllowedBranch;
use iProtek\Core\Models\Branch;
use iProtek\Core\Http\Controllers\_Common\_CommonOwnGroupController;
use iProtek\Core\Helpers\PayModelHelper;

class SharedAccountAllowedBranchController extends _CommonOwnGroupController
{
    //
    public function get_shared_account_allowed_own_branches(Request $request){

        $shared_account_id = $request->shared_account_id;
        $getData = $this->getOwn( SharedAccountAllowedBranch::class, $request)->where(["shared_account_id"=>$shared_account_id,"is_allowed"=>1]);
        return $getData->get();

    }

    public function set_shared_account_allowed_branch(Request $request){
        $shared_account_id = $request->shared_account_id;
        $billing_branch_id = $request->billing_branch_id;
        $is_allowed = $request->is_allowed ? 1 : 0;
        //return ["status"=>1, "messag"=>"G", "billing_branch_id"=> ];
        $billing_branch = $this->getValidOwn(Branch::class, $request, ["id"=>$billing_branch_id]);

        //$allowed = SharedAccountAllowedBranch::where([""])
        $has_shared = PayModelHelper::get_own(SharedAccountAllowedBranch::class, $request, ["shared_account_id"=>$shared_account_id, "billing_branch_id"=>$billing_branch_id])->first();
        if(!$has_shared){
            $has_shared = PayModelHelper::create_own(SharedAccountAllowedBranch::class, $request, ["shared_account_id"=>$shared_account_id, "billing_branch_id"=>$billing_branch_id,"is_allowed"=>$is_allowed]);
        }else{
            PayModelHelper::update_own($has_shared, $request, ["is_allowed"=>$is_allowed]);
        }


        return["status"=>1, "message"=>"Successfully Allowed"];
    }


}
