<?php
namespace iProtek\Core\Enums;

use DB;  

class FileImportBatchStatus
{
    public const PENDING = 0;
    public const SUCCEED = 1; 
    public const FAILED = 2; 
    public const PROCESSING = 3;
    public const STOPPED = 4; 

}