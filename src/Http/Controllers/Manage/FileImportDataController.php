<?php

namespace iProtek\Core\Http\Controllers\Manage;

use Illuminate\Http\Request;
use iProtek\Core\Http\Controllers\_Common\_CommonController;
use iProtek\Core\Models\FileImportData;
use iProtek\Core\Models\FileImportBatch;

class FileImportDataController extends _CommonController
{
    //
    public $guard = 'admin';

    public function list(Request $request){

        $batch = FileImportBatch::select(
            'id',
            'target_field',
            'line_succeed', 
            'line_failed',
            'line_valid',
            'settings'
        )->find($request->file_import_batch_id);

        $importData = FileImportData:: where([ 
            "file_import_batch_id" => $request->file_import_batch_id 
        ]);
        
        //SEARCHES
        if($request->search){
            $search = '%'.str_replace(' ', '%', $request->search).'%';
            $importData->whereRaw(" IFNULL(json_data,'') LIKE ? ", [$search]);
        }

        //FOR COUNTING Purpse while the status is not set.
        $NoStatusData = (clone $importData);

        if($request->status_id != -1){ 

            $status_id = $request->status_id ?: 0; 

            $importData->where('status_id', $request->status_id); // Constraint for status

        }


        return [
            "batch"=>$batch,
            "failed_count"=>   (clone $NoStatusData)->where('status_id', 2)->count(),
            "succeed_count"=>  (clone $NoStatusData)->where('status_id', 1)->count(),
            "pending_count"=>  (clone $NoStatusData)->where('status_id', 0)->count(),
            "paginate"=> $importData->paginate(10)
        ];
    }

}
