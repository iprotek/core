<?php

namespace iProtek\Core\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AppInfoController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function app_info(Request $request){ 
        $result = [
            "version"=>config("api_version","1.0.0.0"),
            "db-version"=>config("api_db_version","1.0.0.0"),
            "name"=> config("app.name"),
            "description"=>config("api_description")
        ];
        return $result;
    }


}
