<?php
namespace iProtek\Core\Traits;
use Illuminate\Support\Facades\Log;

trait iProtekCommonTraits
{
    public $commonFillable = [
        "group_id",
        "pay_created_by",
        "pay_updated_by",
        "pay_deleted_by",
        "branch_id"
    ];
    
    protected static function booted()
    {
        /*
        $commonFillable = static::$commonFillable;
        static::creating(function ($model) use($commonFillable) {
            $model->mergeFillable( $commonFillable );
            //Log::error($model->fillable);
        });
        */
    }

    public function initializeHasCommonAttributes()
    {
        // Merge with child model's $fillable (if defined)
        //$this->mergeFillable($this->commonFillable);
        
        $this->fillable = array_merge(
            $this->fillable ?? [],
            array_diff($this->commonFillable ?? [], $this->fillable ?? [])
        );
        //if($this->getTable() == "sys_notify_schedulers")
          //  Log::error($this->fillable);
    }

}