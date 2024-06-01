<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ProcUpdateListSelect extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        DB::unprepared("DROP PROCEDURE IF EXISTS `spListSelect`");
        DB::unprepared("
CREATE PROCEDURE `spListSelect`(
    _ControllerName varchar(200),
    _LoginID INT,
    _Json TEXT,
    _SearchInfo text,
    _PageNo INT,
    _ItemsPerPage INT
)
BEGIN
    /*
        SeachJSON
        {
            \"SearchText\":\"\" //Barcode
        }
    */
    DECLARE _RESULT LONGTEXT;
    DECLARE _ITEMSFOUND INT;
    DECLARE _SearchText TEXT;    
    
    SET _SearchText = fnJSON_VALUE(_SearchInfo, '$.SearchText');
    #SET _ItemType = IFNULL( fnJSON_VALUE(_SearchInfo, '$.ItemType'), '');
    SET SESSION group_concat_max_len = 100000000;
    SET _ITEMSFOUND = 0;
    ####################################################################################
    ##
    #################################################################
    #SET _RESULT =  fnBarcodeOnlySearch(_SearchText);
    /*
    SELECT JSON_OBJECT(
    'CategoryList', JSON_EXTRACT(fnListCategories(),'$'),
    'FactoryList', JSON_EXTRACT(fnListFactories(),'$'),
    'ClassificationList', JSON_EXTRACT(fnListClassifications(),'$'),
    'EvaluationList', JSON_EXTRACT(fnListEvaluations(),'$'),
    'FocusAreaList', JSON_EXTRACT(fnListFocusAreas(),'$'),
    'ImplementationStatusList', JSON_EXTRACT(fnListImplementationStatuses(),'$'),
    'DepartmentList', JSON_EXTRACT(fnListDepartments(),'$'),
    'RegionList', JSON_EXTRACT(fnListRegions(),'$'),
    'LineList', JSON_EXTRACT(fnListLineSection(),'$'),
    'PositionList', JSON_EXTRACT(fnListPositions(),'$'),
    'PositionsDepartments', JSON_EXTRACT(fnListDepartmentsPositions(),'$')
    ) INTO _RESULT;
    */
    
    ####################################################################################
    #SET _RESULT = `fnSysResultSearching`(_RESULT, _SearchText );
    SET _ITEMSFOUND =  JSON_LENGTH(_RESULT);
    #SET _RESULT = `fnSysResultPaging`(_RESULT, _PageNo, _ItemsPerPage);
    ############################## RESULT ####################################################
    SELECT 
        IFNULL(_RESULT,'[]') as `Data`,
        'Successfully Retrieved' as `Message`,
        1 as RetVal, 
        1 as ReturnValue, 
        _PageNo as `Page`,
        _ItemsPerPage as ItemPerPage, 
            IFNULL(_ITEMSFOUND, 0) as ItemsFound;
END
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
