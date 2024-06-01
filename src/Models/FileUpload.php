<?php

namespace iProtek\Core\Models;

use iProtek\Core\Models\_CommonModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FileUpload extends _CommonModel
{
    use HasFactory, SoftDeletes;

    public $fillable = [
        "group_id",
        "pay_created_by",
        "pay_updated_by",
        "pay_deleted_by",

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
        return "/manage/file-uploads/image-preview/".$this->id;
    }
    public function getPublicLinkAttribute(){
        return "/image-preview/".$this->id;
    }
}
