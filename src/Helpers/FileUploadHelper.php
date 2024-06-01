<?php

namespace App\Helpers;

use DB;
use App\Models\FileUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileUploadHelper
{

    //GETTING DATA FROM A TABLE
    public static function suggestion( Request $request, $suggestion_id)
    {
        $resume_docs = $request->file('attachments');
        //return count($resume_docs);
        $has_upload = false;

        foreach($resume_docs as $doc)
        {
            $has_upload = true;
            
            $doc_data =  FileUpload::create(
                [
                    "target_name_id" => 1,// Suggestion Attachment
                    "target_id" => $suggestion_id,
                    "file_name" => $doc->getClientOriginalName(),
                    "file_ext" => $doc->getClientOriginalExtension()
                ]
            );
            Storage::disk('local')->putFileAs(
                'attachment/files',
                $doc,
                $doc_data->id.".".$doc_data->file_ext
            );
            //$doc->store('users/' . $this->user->id . '/messages');
        }
        return $has_upload;
    }

    //GETTING DATA FROM A TABLE
    public static function set_html_content( $content, $target_id, $target_name, $file_name = 'htmlcontent.txt')
    {
        //$target_name = 'contact_us_content';
        $fileUpload = FileUpload::where('target_id', $target_id)->where('target_name', $target_name)->first();
        if(!$fileUpload){
            $fileUpload = FileUpload::create([
                "target_name"=>$target_name,
                "target_id"=>$target_id,
                "file_type"=>"text/plain",
                "file_name"=>$file_name,
                "file_ext"=>"txt",
                "location"=>"",
                "created_by"=>auth()->user()->id
            ]);
        }else{
            $fileUpload->created_by = auth()->user()->id;
            $fileUpload->save();
        }
        
        $txt_file_name = $fileUpload->target_id."_".$fileUpload->id.".".$fileUpload->file_ext;
        Storage::disk('local')->put(
            'images/'.$txt_file_name, $content
        );
        //DELETE THE BACKUP DATA TRIGGER TO OVERWRITE
        \App\Models\CloudData::where('file_upload_id', $fileUpload->id )->where('file_name', $txt_file_name)->delete();
         
    }

    public static function get_html_content($target_id, $target_name){
        //$target_name = 'contact_us_content';
        $content_data = "";
        $fileUpload = FileUpload::where('target_id', $target_id)->where('target_name', $target_name )->first();
        if($fileUpload){
            $txt_file_name = $fileUpload->target_id."_".$fileUpload->id.".".$fileUpload->file_ext;
            $content_data = Storage::disk('local')->get(
                'images/'.$txt_file_name
            );
        }
        return $content_data;

    }
}
