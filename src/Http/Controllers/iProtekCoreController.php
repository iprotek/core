<?php

namespace iProtek\Core\Http\Controllers;

use iProtek\Core\Http\Controllers\Controller;
use Illuminate\Http\Request;

class iProtekCoreController extends Controller
{
    public function index(Request $request)
    {
        return view('iprotek_core::index');
    }
}