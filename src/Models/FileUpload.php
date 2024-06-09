<?php

namespace iProtek\Core\Models;

use iProtek\Core\Models\_CommonModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FileUpload extends _CommonModel
{
    use HasFactory, SoftDeletes;

    public $fillable = [
        "target_name",
        "target_id",
        "order_no",
        "file_type",
        "file_name",
        "file_ext",
        "is_default",
        "location",
        "created_by",
        "deleted_by"
    ];
    
    public $casts = [
        "created_at" => "datetime:Y-m-d H:i",
        "updated_at" => "datetime:Y-m-d H:i"
    ];

    public $appends = [
        "link",
        "public_link"
    ];

    public function getLinkAttribute(){

        //if(substr($this->file_type,0, 5) == 'image'){ 
            return "/storage/images/".$this->target_id."_".$this->id.".".$this->file_ext;
        //}
        //return "/storage/images/0blank.png";
        //return "/manage/file-uploads/image-preview/".$this->id;
    }
    public function getPublicLinkAttribute(){
        
        //if(substr($this->file_type,0, 5) == 'image'){ 
            return "/storage/images/".$this->target_id."_".$this->id.".".$this->file_ext;
        //}
        //return "/storage/images/0blank.png";
        //return "/image-preview/".$this->id; 
    }
}
