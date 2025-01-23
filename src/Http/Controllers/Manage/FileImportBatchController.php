<?php

namespace iProtek\Core\Http\Controllers\Manage;

use Illuminate\Http\Request;
use iProtek\Core\Http\Controllers\_Common\_CommonController;
use iProtek\Core\Models\FileImportBatch;
use iProtek\Core\Helpers\PayHttp;
class FileImportBatchController extends _CommonController
{
    //
    public $guard = 'admin';

    public function list(Request $request){
        $fileImports = FileImportBatch::with(['created_by'=>function($q){
            $q->select('id', 'user_admin_id', 'pay_app_user_account_id');
        },'updated_by'=>function($q){
            $q->select('id', 'user_admin_id', 'pay_app_user_account_id');
        }]);
        
        //return "Hello";
        if($request->search){
            $search = '%'.$request->search.'%';
            $fileImports->whereRaw(" concat('#',id,'#', file_name, target_field) like ? ", [$search]);
        }

        $fileImports->orderByRaw(' FIELD(status_id, 0, 3 )  DESC');

        $fileImports->orderByRaw(' created_at  DESC ');

        return $fileImports->paginate(10);
    }

    public function add(Request $request){

        //FILE VALIDATION
        $this->validate($request, [ 
            "file"=>"required|file|mimes:csv|max:5120"
        ], [
            'file.mimes' => 'Only CSV file is allowed and Please also double check the header that should be at first line.' 
        ]);

        //DETAILS VALIDATION
        $data = $this->validate($request, [
            "file_name"=>"required",
            "file_ext"=>"required",
            "target_field"=>"required",
            "settings"=>"required"
        ])->validated();

        $file = $request->file('file');

        $countLines = count( file($file->getRealPath()) );
        $data["total_lines"] = $countLines;

        //SET THE IMPORTER
        $data['pay_created_by'] = PayHttp::pay_account_id();
 
        //CREATE FILE
        $fileBatch = FileImportBatch::create($data); 

        $file->storeAs("imports", $fileBatch->id.".". $request->file_ext);
        return ["status"=>1, "message"=>"Successfully Imported"];
    }

    public function action_retry(Request $request){
        $impBatch = FileImportBatch::find($request->file_import_batch_id);
        if(!$impBatch ){
            return ["status"=>0, "message"=>"Batch not found."]; 
        }
        else if($impBatch->status_id == 0){
            return ["status"=>0, "message"=>"Already on queue."]; 
        }
        $impBatch->status_id = 0;
        $impBatch->pay_updated_by = PayHttp::pay_account_id();
        $impBatch->status_info ="Retried for Pending import.";
        $impBatch->interfer_at = \Carbon\Carbon::now();
        $impBatch->save();
        return ["status"=>1, "message"=>"Retried Successfully"];
    }

    public function action_start(Request $request){
        
        $impBatch = FileImportBatch::find($request->file_import_batch_id);
        if(!$impBatch ){
            return ["status"=>0, "message"=>"Batch not found."]; 
        }
        else if($impBatch->status_id == 4){
            return ["status"=>0, "message"=>"Already Stopped."]; 
        }

        FileImportBatch::where('status_id',3)->update([
            "status_id"=>0,
            "status_info"=>"On queued for prioritizing Batch#".$impBatch->id,
            "interfer_at"=>\Carbon\Carbon::now()
        ]);


        $impBatch->status_id = 3;
        $impBatch->pay_updated_by = PayHttp::pay_account_id();
        $impBatch->status_info ="Start Manually.";
        $impBatch->interfer_at = \Carbon\Carbon::now();
        $impBatch->save();

        return ["status"=>1, "message"=>"Start Successfully".$request->file_import_batch_id];

    }

    public function action_stop(Request $request){
        $impBatch = FileImportBatch::find($request->file_import_batch_id);
        if(!$impBatch ){
            return ["status"=>0, "message"=>"Batch not found."]; 
        }
        else if($impBatch->status_id == 4){
            return ["status"=>0, "message"=>"Already Stopped."]; 
        }
        $impBatch->status_id = 4;
        $impBatch->pay_updated_by = PayHttp::pay_account_id();
        $impBatch->status_info ="Stopped Manually.";
        $impBatch->interfer_at = \Carbon\Carbon::now();
        $impBatch->save();


        return ["status"=>1, "message"=>"Stopped Successfully".$request->file_import_batch_id]; 
    }


}
