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
        $fileImports = FileImportBatch::on();
        
        //return "Hello";
        if($request->search){
            $search = '%'.$request->search.'%';
            $fileImports->whereRaw(" concat('#',id,'#', file_name, 'target_field') like ? ", [$search]);
        }

        return $fileImports->paginate(10);
    }

    public function add(Request $request){

        //FILE VALIDATION
        $this->validate($request, [ 
            "file"=>"required|file|mimes:csv,xlsx,xls|max:5120"
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


}
