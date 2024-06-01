<?php

namespace iProtek\Core\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class iProtekCore extends Controller
{
    public function index(Request $request)
    {
        return view('iprotek_core::index');
    }
}