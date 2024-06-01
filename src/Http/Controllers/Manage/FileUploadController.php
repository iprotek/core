<?php

namespace iProtek\Core\Http\Controllers\Manage;

use Illuminate\Http\Request;
use iProtek\Core\Http\Controllers\_Common\_CommonController;
use DB;
use iProtek\Core\Models\FileUpload;
use Illuminate\Support\Facades\Storage; 
use iProtek\Core\Helpers\PayModelHelper;
use Illuminate\Support\Facades\File;
//use Illuminate\Support\Facades\Response;


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
        return null;
        $filename = $id->target_id."_".$id->id.".".$id->file_ext;
        $path = 'images/' . $filename;
        if (Storage::disk('local')->exists($path)) {
            // You can customize the headers, e.g., to force download instead of displaying in the browser
            // You can remove the following two lines if you want to display the image directly in the browser.
            if(substr($id->file_type,0, 5) == 'image'){
                $file = Storage::disk('local')->get($path);
                $headers = [
                    'Content-Type' =>$id->file_type,
                ];
                return response($file, 200, $headers);
            }
            else{
                $file = Storage::disk('local')->get('images/0blank.png');
                $headers = [
                    'Content-Type' =>"image/png",
                ];
                return response($file, 200, $headers);

            }
            abort(403,"Not Image Format");
        }

        abort(404);

    }

    public function sample_preview(){
        
        foreach (headers_list() as $header){
            $str = explode(':', $header);
            header_remove($str[0]);
        }
        header("Content-Type: image/png");
        die(  trim( file_get_contents("http://localhost:9011/storage/images/18_6.png")) );
       
    }
    
    public function pay_image_preview(Request $request,$group_id, FileUpload $id){

        if($id->target_id != $request->group_id ){
            abort(404);
        } 
        //return $id;
        $filename = $id->target_id."_".$id->id.".".$id->file_ext;
        $path = 'public\\images\\' . $filename;
        if (Storage::disk('local')->exists($path)) {
            // You can customize the headers, e.g., to force download instead of displaying in the browser
            // You can remove the following two lines if you want to display the image directly in the browser.
             
            if(substr($id->file_type,0, 5) == 'image'){
                $path = Storage::disk('local')->path($path);
                //abort(404);
                //return $path;
                header("Content-Type: image/png"); 
                echo file_get_contents("C:/Projects/Website/Laravel/marketing.iprotek.net/storage/app/images/18_1.png");
                die();
                return $id->file_type;
                return response()->file($path);
                return $path ;

                $file = Storage::disk('local')->get($path);
                $headers = [
                    'Content-Type' =>$id->file_type,
                ];
                //abort(403, 'FOUND');
                return $file;
                return response()->file($path);
                return response($file, 200, $headers);
            }
            else{
                $file = Storage::disk('local')->get('images/0blank.png');
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
        $path = 'images/' . $filename;
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
        return ["status"=>1, "data"=>"", "message"=>"Default set."];
    }

    public function remove(Request $request, FileUpload $id){
        $id->deleted_by = auth()->user()->id;
        $id->save();
        $id->delete();
        return ["status"=>1, "data"=>"", "message"=>"Deleted"];
    }

    public function add(Request $request){
        
        if(!auth()->check()){
            abort(404);
        }

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
            'images', $request->file('file'), $fileUpload->target_id."_".$fileUpload->id.".".$fileUpload->file_ext
        );

        return ["status"=>1, "data"=>"", "message"=>"Image Successfully Added."];

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

        return [
            "status"=>1, 
            "data"=>"", 
            "message"=>"Image Successfully Added.", 
            "url"=> route('mainpage').Storage::url('images/'.$fileUpload->target_id."_".$fileUpload->id.".".$fileUpload->file_ext)
        ];

    }

    public function image_view($file_name){
        
        if(Storage::disk('local')->exists('important/'.$file_name)){
            $file = Storage::disk('local')->get('important/'.$file_name);
            if($file){
                $headers = [
                    'Content-Type' =>"image/jpeg",
                ];
                return response($file, 200, $headers);
            }
        }
        abort(404);
    }

    public function get_from_cloud(Request $request, $location, $filename , $storage = 'google-cloud'){

       
       if($storage == 'google-cloud'){
            $object =  \iProtek\Core\Helpers\CloudDataHelper::getFileGoogleCloudStorage($filename, $location);

            if(!$object){
                abort(403, 'File not exists');
            }

            $contentType = $object->info()['contentType'];
            // Set the appropriate response headers
            //For viewing image
            if( strtolower( substr($contentType,0, 5) ) == 'image'){ 
                $headers = [
                    'Content-Type' =>$contentType,
                    'Content-Disposition' => 'inline; filename="' . $filename . '"',
                ]; 
            }
            //For Downloading
            else{
                $headers = [
                    'Content-Type' => $object->info()['contentType'],
                    'Content-Disposition' => 'inline; filename="' . $filename . '"',
                ];
            }

            return response($object->downloadAsStream(), 200, $headers);

        }
        //return "HELLO";    
    }

    public function get_cloud_file_upload(Request $request, $file_upload_id, $storage = 'google-cloud'){
        
        $file =  \iProtek\Core\Helpers\CloudDataHelper::getCloudFileUploads($file_upload_id, $storage);

        if(!$file){
            abort(403, 'File not exists');
        }
        $object = $file['object'];


        $contentType = $object->info()['contentType'];
        // Set the appropriate response headers
        //For viewing image
        if( strtolower( substr($contentType,0, 5) ) == 'image'){ 
            $headers = [
                'Content-Type' =>$contentType,
                'Content-Disposition' => 'inline; filename="' .$file['filename'] . '"',
            ]; 
        }
        //For Downloading
        else{
            $headers = [
                'Content-Type' => $object->info()['contentType'],
                'Content-Disposition' => 'inline; filename="' .$file['filename'] . '"',
            ];
        }

        return response($object->downloadAsStream(), 200, $headers);
    }


}
