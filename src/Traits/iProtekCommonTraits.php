<?php
namespace iProtek\Core\Traits;

trait iProtekCommonTraits
{
    protected $commonFillable = [
        "group_id",
        "pay_created_by",
        "pay_updated_by",
        "pay_deleted_by",
        "branch_id"
    ];

    public function initializeHasCommonAttributes()
    {
        // Merge with child model's $fillable (if defined)
        $this->fillable = array_merge(
            $this->fillable ?? [],
            array_diff($this->commonFillable ?? [], $this->fillable ?? [])
        );
    }

}