<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
//use App\Http\Controllers\Manage\BillingSharedAccountDefaultBranchController;

 
Route::prefix('/company-details')->name('.company-details')->group(function(){
            
    Route::get('/terms-and-conditions', function(Request $request){
        return \iProtek\Core\Helpers\CompanyDetailsHelper::get_terms_and_conditions();
    })->name('.terms-and-conditions')
                ->defaults("_description","List of apps")
                ->defaults("_is_visible",false)
                ->defaults("_is_allow",true);

});