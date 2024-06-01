<?php
namespace iProtek\Core\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use DB; 
use iProtek\Core\Helpers\StoredProcedureCall;
use Illuminate\Support\Facades\Auth;


class SystemDataController extends Controller
{
    public $procedureCall;
    public $UnRestricted;
    public $NonAdminRestricted;
    

    public $loginID = 0;
    public $loginToken = null;
    public $loginType = null;
    public $loginData = null;
    public function __construct()
    {
        //$this->middleware('auth');
        $this->UnRestricted = array( "Door","EmployeeInputPassword","EmployeeVerifyCode","A_AdminUserControlAccess", "EmployeeLogin","AdminLogin","EmployeePayslipLoadList", "PayrollPeriodList", "A_ControlAccess", "SysIndexGroupAndNamesInfo");
        $this->NonAdminRestricted = array();


        $this->procedureCall = new StoredProcedureCall;
        
        $this->loginID = 0;
        $this->loginToken = '';
        $this->loginType = -1;

        //FOR LOGIN PURPOSE
         if(isset($_COOKIE["LoginID"]))
            $this->loginID = $_COOKIE["LoginID"];
        
        if(isset($_COOKIE["LoginToken"]))
            $this->loginToken = $_COOKIE["LoginToken"];
        
        if(isset($_COOKIE["LoginType"]))
            $this->loginType = $_COOKIE["LoginType"];

        $this->loginData = array( "LoginID"=>$this->loginID, "LoginToken"=> $this->loginToken, "LoginType"=> $this->loginType );

    }
    
    public function APICheck($link)
    {
        if(substr($link,0,3) != "API")
            abort(404, 'NOT FOUND');
    }

    public function DataPostAPI(Request $request, $link)
    {
        $this->APICheck($link);

        return $this->DataPost($request, $link);
    }
    //FOR INSERTING
    public function DataPost(Request $request, $link)
    {
       
        /**RESTRICTION FOR LOGINS TO what data do you want to get*/
        if(!in_array($link, $this->UnRestricted))
        {
            $this->middleware('auth');
            //$postData = $this->Validated();
            //if($postData['Retval'] != "1")
            //{
           //    return  $postData;
           // }
            //Admin Restriction can only go inside
           // if(!in_array($link,  $this->NonAdminRestricted ))
           // {            
            //    if(! $this->isAllowed($link, 'add') )
            //    {
            //        return 'Error has occured';
            //    }
            //}

        }

        $user = Auth::guard("admin")->user();
        if($user != null)
        {
            $this->loginID = $user->id;
        }
        //MOST VALUABLE PART
        $requestContent = $request->getContent();

        //FOR TESTING
        //return  ["data"=> $requestContent, "link"=> $link] ;
        $proc_result = $this->procedureCall->DataCommand( $link, $this->loginID, $requestContent, 'ADD');

        $this->procedureCall = null;
        return $proc_result;
    
    }


    public function DataPutAPI(Request $request, $link)
    {
        $this->APICheck($link);

        return $this->DataPut($request, $link);
    }
    //FOR EDIT
    public function DataPut(Request $request, $link)
    {
        /**VERIFY LOGIN*/
       

         /**RESTRICTION FOR LOGINS */
        if(!in_array($link, $this->UnRestricted))
        {
            $this->middleware('auth'); 
            //$postData = $this->Validated();
            //if($postData['Retval'] != "1")
           // {
            //    return  $postData;
           // }
            //Second verification Layer
           // if(! $this->isAllowed($link, 'edit') )
           // {
            //    return 'Error has occured';
            //}
        }
        //MOST VALUABLE PART
        $requestContent = $request->getContent();
        
        $user = Auth::guard("admin")->user();
        if($user != null)
        {
            $this->loginID = $user->id;
        }
        //FOR TESTING
        //return  ["data"=> $requestContent, "link"=> $link] ;
        
        $proc_result = $this->procedureCall->DataCommand( $link, $this->loginID, $requestContent, 'EDIT');
        $this->procedureCall = null;
        return $proc_result;

    }


    
    public function DataGetAPI($link, $pageNo, $itemsNo, $jsonSearch)
    {
        $this->APICheck($link);

        return $this->DataGet($link, $pageNo, $itemsNo, $jsonSearch);
    }
    //URL SEARCHING WITH SLASH
    public function DataGet($link, $pageNo, $itemsNo, $jsonSearch)
    {

        /**RESTRICTION FOR LOGINS
        if(!in_array($link, $this->UnRestricted))
        {
            $this->middleware('auth');
           ///
            $postData = $this->Validated();
            if($postData['Retval'] != "1")
            {
                return  $postData;
            }
            
            //Second verification Layer
            if(! $this->isAllowed($link, 'view') )
            {
                return 'Error has occured'.$link;
            }
        }
        */

        //TESTING FOR THE LINK
        //return [ "link"=> $link, "pageNo"=> $pageNo, "itemCount"=>$itemsNo, "jsonSearch"=>$jsonSearch ];

        $user = Auth::guard("admin")->user();
        if($user != null)
        {
            $this->loginID = $user->id;
        }
        $proc_result = $this->procedureCall->DataSelect($link, $this->loginID, $this->loginData, $jsonSearch, $pageNo,  $itemsNo);
        //DISPOSAL
        $this->procedureCall = null;
        return $proc_result;
        
    }



    
    public function DataGet_QSAPI(Request $request,$link)
    {
        $this->APICheck($link);

        return $this->DataGet_QS($request,$link);
    }
    ///THIS IS FOR QUERY SEARCHING
    function DataGet_QS(Request $request,$link)
    {
        return $this->DataGet($link, $request['pageNo'], $request['itemCount'], $request['jsonSearch']);
    }

    public function Validated(){
        
        /** Test Validity*/
        $valid = $this->procedureCall->DataSelect('LoginVerify', $this->loginID, json_encode($this->loginData), '{}', 0, 0);
        return $valid;
    }

    function isAllowed($ControlName, $AccessName)
    {
        if($this->loginType == "0")
        {
            $proc_res =  $this->procedureCall->DataCommand( 'A_IsAlloweAccess', $this->loginID, array("ControlName"=>$ControlName,"ControlAccessName"=>$AccessName), 'ADD');
            return $proc_res['RetVal']=="1";
        }
    }
    function Display()
    {
        return 'Hello';
    }
    
}