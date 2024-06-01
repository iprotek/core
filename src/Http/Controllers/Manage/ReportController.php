<?php

namespace iProtek\Core\Http\Controllers\Manage;

use Illuminate\Http\Request;
use iProtek\Core\Http\Controllers\_Common\_CommonController;
use iProtek\Core\Models\Invoice;
use iProtek\Core\Models\BookingBundle;
use iProtek\Core\Models\WebVisitor;
use iProtek\Core\Models\Customer;
use DB;

class ReportController extends _CommonController
{
    //
    public $guard = 'admin';
    public function __construct()
    { 
    }
    public function index(Request $request){

        return $this->view('manage.reports.index');
    } 
}
