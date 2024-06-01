<?php

namespace App\Helpers;

use DB;

class MailStatusHelper
{
    public const PENDING = 0;
    public const SENDING = 1;
    public const SUCCESS = 2; 
    public const FAILED = 3;
}
