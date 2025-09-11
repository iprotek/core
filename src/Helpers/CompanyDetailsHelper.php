<?php

namespace iProtek\Core\Helpers;

use DB;
use iProtek\Core\Models\FileUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; 

class CompanyDetailsHelper
{

    //GETTING DATA FROM A TABLE
    public static function set_terms_and_condition( $content)
    {
        $fileUpload = FileUpload::where('target_id', 1)->where('target_name', 'terms_and_conditions_content')->first();
        if(!$fileUpload){
            $fileUpload = FileUpload::create([
                "target_name"=>"terms_and_conditions_content",
                "target_id"=>1,
                "order_no"=>0,
                "file_type"=>"text/plain",
                "file_name"=>"htmlcontent.txt",
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
        \iProtek\Core\Models\CloudData::where('file_upload_id', $fileUpload->id )->where('file_name', $txt_file_name)->delete();
         
    }

    public static function get_terms_and_conditions(){
        
        $content_data = "";
        $fileUpload = FileUpload::where('target_id', 1)->where('target_name', 'terms_and_conditions_content')->first();
        if($fileUpload){
            $txt_file_name = $fileUpload->target_id."_".$fileUpload->id.".".$fileUpload->file_ext;
            $content_data = Storage::disk('local')->get(
                'images/'.$txt_file_name
            );
        }
        return $content_data;

    }

    //GETTING DATA FROM A TABLE
    public static function set_contact_us( $content)
    {
        $target_name = 'contact_us_content';
        $fileUpload = FileUpload::where('target_id', 1)->where('target_name', $target_name)->first();
        if(!$fileUpload){
            $fileUpload = FileUpload::create([
                "target_name"=>$target_name,
                "target_id"=>1,
                "file_type"=>"text/plain",
                "file_name"=>"htmlcontent.txt",
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
        \iProtek\Core\Models\CloudData::where('file_upload_id', $fileUpload->id )->where('file_name', $txt_file_name)->delete();
         
    }

    public static function get_contact_us(){
        $target_name = 'contact_us_content';
        $content_data = "";
        $fileUpload = FileUpload::where('target_id', 1)->where('target_name', $target_name )->first();
        if($fileUpload){
            $txt_file_name = $fileUpload->target_id."_".$fileUpload->id.".".$fileUpload->file_ext;
            $content_data = Storage::disk('local')->get(
                'images/'.$txt_file_name
            );
        }
        return $content_data;

    }
}
