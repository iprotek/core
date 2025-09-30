<?php

namespace iProtek\Core\Http\Controllers\Manage;

use Illuminate\Http\Request;
use iProtek\Core\Http\Controllers\_Common\_CommonController;
use iProtek\Core\Models\Tagging;
use iProtek\Core\Helpers\PayModelHelper;

class TaggingDescriptionController extends _CommonController
{
    //
    public $guard = "admin";
}
