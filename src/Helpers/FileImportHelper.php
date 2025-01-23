<?php

namespace iProtek\Core\Helpers;

use DB;
use iProtek\Core\Models\FileImportBatch;
use iProtek\Core\Models\FileImportData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;

class FileImportHelper
{

    public static $uploadPerMinute = 300;

    public static function startProcessing(){

        //Priority the processing..
        $file_batch = FileImportBatch::whereIn('status_id', [0, 3])->orderBy('status_id', 'DESC')->first();
        if($file_batch && $file_batch->line_processing < $file_batch->total_lines){

            if($file_batch->status_id == 0){

                $file_batch->status_id = 3;

                $file_batch->save();

            }

            //START UPLOADING 100 item per minute
            $file_path = $file_batch->file_path;

            
            $reader = IOFactory::createReader('Csv');
            $spreadsheet = $reader->load($file_path);

            // Get the active sheet
            $sheet = $spreadsheet->getActiveSheet();

            // Get all rows starting from the second row
            $rows = $sheet->toArray(null, true, true, true); // Returns data and headers
            //$headers = $rows[0]; //$sheet->rangeToArray('A1:ZZ1', null, true, true, false)[0];
            $headers = [];
            foreach( $rows[1] as  $val ){
                $headers[] = $val;
            }

            if(count($headers)<=0){ 

                $file_batch->status_id = 2;
                
                $file_batch->status_info = "No headers found!";

                $file_batch->save();

                return;
            
            }

            //FOR REUSABILITY VARIABLE
            $line_process = $file_batch->line_processing;
            
            // Create a new array with the custom headers
            $data = array_map(function($row) use ($headers) {
                return array_combine($headers, $row);  // Combine headers and data into an array
            }, array_slice($rows, 1 + $line_process, static::$uploadPerMinute )); 

            $line_process = $line_process == 0 ? 1 : $line_process;
            $line_valid = $file_batch->line_valid;

            //SAVE TO FILE IMPORT DATA
            if(count($data)>0){
                foreach($data as $row){
                    try{

                        $line_process++;
                        $line_valid++;
                        FileImportData::create( [
                            "json_data"=>json_encode($row),
                            "file_import_batch_id"=>$file_batch->id,
                            "status_id"=>0,
                            "line_no"=>$line_process
                        ]);
                        

                    }catch(\Exception $ex){

                        $file_batch->line_processing = $line_process--;
                        $file_batch->status_id = 2;
                        $file_batch->status_info = $ex->getMessage();
                        $file_batch->save(); 

                        return;
                    }
                }
                
                $file_batch->line_processing = $line_process;
                $file_batch->line_valid = $line_valid;
                $file_batch->save();
            }

        }

    }
}
