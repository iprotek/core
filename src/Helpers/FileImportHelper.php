<?php

namespace iProtek\Core\Helpers;

use DB;
use iProtek\Core\Models\FileImportBatch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class FileUploadHelper
{

    public static $uploadPerMinute = 100;


    public static function startProcessing(){

        //Priority the processing..
        $file_batch = FileImportBatch::whereIn('status_id', [0, 3])->orderBy('status_id', 'DESC')->first();
        if(!$file_batch){
            if($file_batch->status_id == 0){
                $file_batch->status_id = 3;
                $file_batch->save();
            }

            //START UPLOADIN 100 item per minute





        }



    }
}
