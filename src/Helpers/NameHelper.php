<?php

namespace iProtek\Core\Helpers;

use DB;

class NameHelper
{

    //GETTING DATA FROM A TABLE
    public static function TData($table_name, $id, $key='name')
    {
        $result = DB::table($table_name)->find($id);
        if($result == null)
            return "";
        return $result->$key;
          //->${$key};
    }

    ///GETTING DATA FROM A MODEL
    public static function mData($model, $id, $key='name')
    {
        $result = $model::find($id);
        if($result == null)
            return "";
        return $result->$key;
          //->${$key};
    }

}
