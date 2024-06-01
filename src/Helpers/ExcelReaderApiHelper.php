<?php

namespace iProtek\Core\Helpers;

//use Illuminate\Http\Request;
use DB;


class ExcelReaderApiHelper
{

    public static function read_data($file)
    {
        $client = new \GuzzleHttp\Client();
        $name = time() . '_' . $file->getClientOriginalName();
        
        $path1 = $file->store('temp');
        $path=storage_path('app').'/'.$path1;

        $res = $client->request('POST', "http://excel-process.sportscity.com.ph/api/upload-data", [
            //'auth'      => [ env('API_USERNAME'), env('API_PASSWORD') ],
            'multipart' => [
                [
                    'name'     => 'file',
                    'contents' => file_get_contents($path),
                    'filename' => $name 
                ]
            ]
        ]);
        
        if($res->getStatusCode() != 200){ 
            abort(403, "Api endpoint problem.");
            //exit("Something happened, could not retrieve data");
        }
        $response = json_decode($res->getBody());
        return $response;
        //var_dump($response);
    
    }
}