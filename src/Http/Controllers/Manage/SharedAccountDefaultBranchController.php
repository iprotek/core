<?php

namespace iProtek\Core\Http\Controllers\Manage;

use Illuminate\Http\Request;
use iProtek\Core\Models\SharedAccountAllowedBranch;
use iProtek\Core\Helpers\BranchHelper;
use iProtek\Core\Http\Controllers\_Common\_CommonOwnGroupController;


class SharedAccountDefaultBranchController extends _CommonOwnGroupController
{
    //

    public function get_default(Request $request){     
        return BranchHelper::getDefaultBranch($request, false);    
    }

    public function get_default_or_first(Request $request){
        return BranchHelper::getDefaultBranch($request);  
    }

    public function set_default(Request $request){
        return BranchHelper::setDefaultBranch($request);
    }



}
