<?php

namespace iProtek\Core\Http\Controllers\Manage;

use Illuminate\Http\Request;
use iProtek\Core\Http\Controllers\_Common\_CommonController;
use iProtek\Core\Models\FileImportBatch;
use iProtek\Core\Helpers\PayHttp;
use iProtek\Core\Enums\FileImportBatchStatus as BatchStatus; 

class FileImportBatchController extends _CommonController
{
    //
    public $guard = 'admin';

    public function list(Request $request){
        $fileImports = FileImportBatch::on();
        
        //return "Hello";
        if($request->search){
            $search = '%'.$request->search.'%';
            $fileImports->whereRaw(" concat('#',id,'#', file_name, target_field) like ? ", [$search]);
        }

        $fileImports->orderByRaw(' FIELD(status_id, 0, 3 )  DESC');

        $fileImports->orderByRaw(' created_at  DESC ');


        //CLONE COUNT FOR OTHER STATUS HERE
        $all_count = (clone $fileImports)->count();
        $pending_count = (clone $fileImports)->where('status_id', BatchStatus::PENDING)->count();
        $succeed_count = (clone $fileImports)->where('status_id', BatchStatus::SUCCEED)->count();
        $failed_count = (clone $fileImports)->where('status_id', BatchStatus::FAILED)->count();
        $processing_count = (clone $fileImports)->where('status_id', BatchStatus::PROCESSING)->count();
        $stopped_count = (clone $fileImports)->where('status_id', BatchStatus::STOPPED)->count();

        //SELECT BY STATUS
        if(isset($request->status_id)){
            if($request->status_id >= 0){
                $fileImports->where('status_id', $request->status_id);
            }
        }

        $fileImports->with(['created_by'=>function($q){
            $q->select('id', 'user_admin_id', 'pay_app_user_account_id');
        },'updated_by'=>function($q){
            $q->select('id', 'user_admin_id', 'pay_app_user_account_id');
        }]);


        return [ 
            "all_count"=>$all_count,
            "pending_count"=>$pending_count,
            "succeed_count"=>$succeed_count,
            "failed_count"=>$failed_count,
            "processing_count"=>$processing_count,
            "stopped_count"=>$stopped_count,
            "paginate" =>$fileImports->paginate(10)
        ];
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
            "settings"=>"required",
            "group_id"=>"required"
        ])->validated();

        $file = $request->file('file');

        $countLines = count( file($file->getRealPath()) );
        $data["total_lines"] = $countLines;

        //GROUP_ID
        $data['group_id'] = PayHttp::target_own_group_id();//$request->group_id; //$request->get('proxy_group_id');

        //SET THE IMPORTER
        $data['pay_created_by'] = PayHttp::pay_account_id();
 
        //RECONFIGURE SETTINGS
        $data['settings'] = json_decode($data['settings']);

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
        else if($impBatch->status_id == BatchStatus::PENDING ){
            return ["status"=>0, "message"=>"Already on queue."]; 
        }
        $impBatch->status_id = BatchStatus::PENDING;
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
        else if($impBatch->status_id == BatchStatus::PROCESSING ){
            return ["status"=>0, "message"=>"Already Started."]; 
        }

        FileImportBatch::where('status_id', BatchStatus::PROCESSING )->update([
            "status_id"=>0,
            "status_info"=>"On queued for prioritizing Batch#".$impBatch->id,
            "interfer_at"=>\Carbon\Carbon::now()
        ]);


        $impBatch->status_id = BatchStatus::PROCESSING;
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
        else if($impBatch->status_id == BatchStatus::STOPPED){
            return ["status"=>0, "message"=>"Already Stopped."]; 
        }
        $impBatch->status_id = BatchStatus::STOPPED;
        $impBatch->pay_updated_by = PayHttp::pay_account_id();
        $impBatch->status_info ="Stopped Manually.";
        $impBatch->interfer_at = \Carbon\Carbon::now();
        $impBatch->save();


        return ["status"=>1, "message"=>"Stopped Successfully".$request->file_import_batch_id]; 
    }


}
