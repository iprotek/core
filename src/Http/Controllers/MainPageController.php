<?php

namespace iProtek\Core\Http\Controllers;

use Illuminate\Http\Request;
use iProtek\Core\Helpers\CarouselHelper;

class MainPageController extends Controller
{
    //

    public function index(Request $request, $home_uri = 'home'){

        return view('mainpage', ["home_uri"=>$home_uri, "base_uri"=> "home"]);
    } 
}
