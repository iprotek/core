<?php

namespace iProtek\Core\Http\Controllers\Manage;

use Illuminate\Http\Request;
use iProtek\Core\Http\Controllers\_Common\_CommonController;
use DB;
use iProtek\Core\Models\FileUpload;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use iProtek\Core\Helpers\PayModelHelper; 
use iProtek\Core\Helpers\AppVarHelper;

class FileUploadController extends _CommonController
{
    //
    public $guard = 'admin';
    public function __construct()
    {
        //$this->middleware('auth');
    }
    public function list(Request $request, $id = null){

        $req = $request;
        $data = FileUpload::with(["created_info"]);
        $data->where("target_name", $req->target_name)->where( "target_id", $req->target_id);//->whereRaw(' deleted_at IS NULL');
        
        if($id){
           return $data->where('id', $id)->first();
        }


        return [ "data"=> $data->get() ];

    }

    public function image_preview(Request $request, FileUpload $id){

        $filename = $id->target_id."_".$id->id.".".$id->file_ext;
        $path = 'public/images/' . $filename;
        if (Storage::disk('local')->exists($path)) {
            // You can customize the headers, e.g., to force download instead of displaying in the browser
            // You can remove the following two lines if you want to display the image directly in the browser.            
            if(substr($id->file_type,0, 5) == 'image'){ 
                $file = Storage::disk('local')->get($path);
                //return $file;
                $headers = [
                    'Content-Type' =>$id->file_type
                ];  
                return response($file, 200, $headers);
            }
            else{
                $file = Storage::disk('local')->get('public/images/0blank.png');
                $headers = [
                    'Content-Type' =>"image/png",
                ];
                return response($file, 200, $headers);

            }
            abort(403,"Not Image Format");
        }

        abort(404);

    }

    public function load_file(Request $request, FileUpload $id){
        $filename = $id->target_id."_".$id->id.".".$id->file_ext;
        $path = 'public/images/' . $filename;
        if (Storage::disk('local')->exists($path)) {
            
            $headers = [
                'Content-Type' =>$id->file_type,
                'Content-Disposition' => 'inline; filename="'.$id->file_name.'"'
            ]; 
            $file = Storage::disk('local')->get($path); 
            return response($file, 200, $headers); 
            
        }
        abort(404);

    }

    public function set_default(Request $request, FileUpload $id){
        FileUpload::where(["target_name"=> $request->target_name, "target_id"=>$request->target_id,"is_default"=>1 ])->update(["is_default"=>0]);
        $id->is_default = 1;
        $id->save();

        //CHECK FOR LOGO
        if($id->target_name == 'business_logos' && $id->is_default == 1){
            AppVarHelper::set("business_logo_url", $id->public_link);
            AppVarHelper::set("business_logo_type", $id->file_type);
        } 

        return ["status"=>1, "data"=>"", "message"=>"Default set."];
    }

    public function remove(Request $request, FileUpload $id){
        $id->deleted_by = auth()->user()->id;
        $id->save();
        $id->delete();
        return ["status"=>1, "data"=>"", "message"=>"Deleted"];
    }

    public function add(Request $request){
         

        $req = $request;
        $this->validate($request, [
            'target_name' => 'required',
            'target_id' => 'required',
            //'fileInput' => 'required|mimes:pdf,doc,docx|max:2048',
        ]);
        //random password
        //$password = Str::random(30); 
        $target_name = $req->input('target_name');
        $target_id = $req->input('target_id');
    

        $max_order_no =  DB::select("SELECT max(order_no) as max_order_no FROM file_uploads WHERE target_name = ? AND target_id = ? AND deleted_at IS NULL",[$target_name, $target_id])[0]->max_order_no;
        if(!$max_order_no){
            $max_order_no = 1;
        }
        else{
            $max_order_no = $max_order_no + 1;
        }
        
        $fileUpload = FileUpload::create([
            "target_name"   => $target_name,
            "target_id"     => $target_id,//$req->target_id,
            "order_no"      => $max_order_no,
            "file_name"     => $req->file_name,
            "file_type"     => $req->file_type,//
            "file_ext"      => $req->file_ext,
            "is_default"    => $max_order_no == 1 ? 1 :0,
            "location"      => "",
            "created_by"    =>auth()->user()->id
        ]);

        Storage::disk('local')->putFileAs(
            'public/images', $request->file('file'), $fileUpload->target_id."_".$fileUpload->id.".".$fileUpload->file_ext
        );

        $url = "";
        if($req->is_local && $req->is_local != 0){
            $url = Storage::url('images/'.$fileUpload->target_id."_".$fileUpload->id.".".$fileUpload->file_ext);
        }else{
            $url = config('app.url').Storage::url('images/'.$fileUpload->target_id."_".$fileUpload->id.".".$fileUpload->file_ext);
        }
        
        if($fileUpload->target_name == 'business_logos' && $fileUpload->is_default == 1){
            AppVarHelper::set("business_logo_url", $fileUpload->public_link);
            AppVarHelper::set("business_logo_type", $fileUpload->file_type);
        }

        return ["status"=>1, "data"=>"", "message"=>"Image Successfully Added.", "url"=>$url];

    }

    public function api_add(Request $request){ 

        $req = $request;
        $this->validate($request, [
            'target_name' => 'required',
            'target_id' => 'required',
            //'fileInput' => 'required|mimes:pdf,doc,docx|max:2048',
        ]);
        //random password
        //$password = Str::random(30); 
        $target_name = $req->input('target_name');
        $target_id = $req->input('target_id');
    

        $max_order_no =  DB::select("SELECT max(order_no) as max_order_no FROM file_uploads WHERE target_name = ? AND target_id = ? AND deleted_at IS NULL",[$target_name, $target_id])[0]->max_order_no;
        if(!$max_order_no){
            $max_order_no = 1;
        }
        else{
            $max_order_no = $max_order_no + 1;
        }
        
        $fileUpload = PayModelHelper::create(FileUpload::class, $request, [
            "target_name"   => $target_name,
            "target_id"     => $target_id,//$req->target_id,
            "order_no"      => $max_order_no,
            "file_name"     => $req->file_name,
            "file_type"     => $req->file_type,//
            "file_ext"      => $req->file_ext,
            "is_default"    => $max_order_no == 1 ? 1 :0,
            "location"      => "",
            "created_by"    =>0
        ]);

        Storage::disk('local')->putFileAs(
            'public\\images', $request->file('file'), $fileUpload->target_id."_".$fileUpload->id.".".$fileUpload->file_ext
        );
        $url = "";
        if($req->is_local && $req->is_local != 0){
            $url = Storage::url('images/'.$fileUpload->target_id."_".$fileUpload->id.".".$fileUpload->file_ext);
        }else{
            $url = config('app.url').Storage::url('images/'.$fileUpload->target_id."_".$fileUpload->id.".".$fileUpload->file_ext);
        }

        return [
            "status"=>1, 
            "data"=>"", 
            "message"=>"Image Successfully Added.", 
            "url"=>$url
        ];

    }
}
