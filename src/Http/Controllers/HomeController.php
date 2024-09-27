<?php

namespace iProtek\Core\Http\Controllers;

use Illuminate\Http\Request;
use iProtek\Core\Http\Controllers\_Common\_CommonController;
use iProtek\Core\Models\SuiteType;

class HomeController extends _CommonController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    //public $guard = 'admin';
    public function __construct()
    {

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
       // return $this->view('dashboard');
    }

    public function suite_types(Request $request, $id = null){
        $suiteTypes = SuiteType::on();
        
        if($id == null)
        {
            $suiteTypes->with(['details','default_price', 'prices'])->orderBy('order_no', 'ASC');
            return $suiteTypes->get();
        }
        else{
            $suiteTypes->where('id', $id);
            $suiteTypes->with(['rooms', 'details', 'default_price']);
            return $suiteTypes->first();
        }
    }


    public function about_us_content(Request $request){
        return view('about-us');
    }

    public function terms_and_conditions(Request $request){
        return view('iprotek_core::terms-and-condition-content');
    }

    public function privacy_policy(Request $request){
        return view('privacy-policy-content');
    }

}
