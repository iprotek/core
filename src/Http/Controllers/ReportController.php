<?php

namespace iProtek\Core\Http\Controllers;

use Illuminate\Http\Request;
use iProtek\Core\Http\Controllers\_Common\_CommonController;

class ReportController extends _CommonController
{
    //
    public $guard = 'admin';
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(Request $request){

        return $this->view('report');
    }
}
