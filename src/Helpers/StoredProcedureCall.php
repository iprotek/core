<?php

namespace iProtek\Core\Helpers;
use DB;
//use Illuminate\Database\Events\StatementPrepared;


class StoredProcedureCall
{

    /**
     * DIRECT QUERY USING STORED PROCEDURE.. VERY CONVENIENT IN MAKING DYNAMIC WEBSITE
     * Providing Defaults:
     *      PageNo          1
     *      ItemPerPage     20
     */
    public function DataSelect($ControlName, $LoginID, $jsonContent, $SearchInfo, $PageNo = 1, $ItemPerPage = 20)
    {
        if($LoginID == 'undefined' || !isset($LoginID))
            $LoginID = 0;

        $jsonContent = is_array($jsonContent) ? json_encode($jsonContent): $jsonContent;
        
        $pdo = DB::connection()->getPdo();
        $queryResult = $pdo->prepare("call sp".$ControlName."Select(?,?,?,?,?,?)");  
        $queryResult->execute( array($ControlName, $LoginID, $jsonContent, $SearchInfo, $PageNo, $ItemPerPage) ); 
        $results = $queryResult->fetch(\PDO::FETCH_ASSOC);
        $pdo = null;
        $queryResult = null;
        
        //$results =  (array)DB::select("call sp".$ControlName."Select(?,?,?,?,?,?)",[$ControlName, $LoginID, $jsonContent, $SearchInfo, $PageNo, $ItemPerPage])[0];
        
        //TRANSOFRMING DATA TEXT OUTPUT INTO JSON
        if( $results['Data'] != null)
                $results['Data'] = json_decode($results['Data']);


        return $results; 
    }
    
    /**
     * VERY USEFUL IN MAKING COMMANDS FOR DYNAMIC WEBSITES
     */
    public function DataCommand($ControllerName, $LoginID, $jsonContent, $Command)
    {
        if($LoginID == 'undefined' || !isset($LoginID))
            $LoginID = 0;

        $jsonContent = is_array($jsonContent) ? json_encode($jsonContent): $jsonContent;

        $pdo = DB::connection()->getPdo();
        $queryResult = $pdo->prepare("call sp".$ControllerName."Command(?,?,?,?)");  
        $queryResult->execute( array($ControllerName,  $LoginID, $jsonContent, $Command) ); 
        $results = $queryResult->fetch(\PDO::FETCH_ASSOC);//All(\PDO::FETCH_ASSOC); 
        $pdo = null;
        $queryResult = null;
        $jsonContent = null;
        return $results;
    }

}


?>