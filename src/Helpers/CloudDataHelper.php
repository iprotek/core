<?php

namespace App\Helpers;

use DB;
use App\Models\FileUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Google\Cloud\Storage\StorageClient;
use Carbon\Carbon;

class CloudDataHelper
{

    public static function GoogleStorageSettings(){
        try{
            $bucket_name = env('GOOGLE_CLOUD_STORAGE_BUCKET');
            if(!$bucket_name){ 
                return [ "status"=>0, "message"=> "Bucket not set" ];  
            }

            $path = env('GOOGLE_CLOUD_STORAGE_JSON_FILE_PATH');
            if(Storage::disk('local')->exists($path)){
                $path = Storage::disk('local')->path($path);
            }else{
                return [ "status"=>0, "message"=> "JSON File Not Found" ];  
            }
            $storage = new StorageClient([
                'keyFilePath' => $path,
            ]);

            $bucket = $storage->bucket($bucket_name);
            
            //if (!$bucket->exists()) {
            //    $storage->createBucket($bucket_name);
            //    $bucket = $storage->bucket($bucket_name);
            //}
            
            return [ "status"=>1, "storage"=> $storage, "bucket"=>$bucket ];
        
        }catch(\Exception $ex){
            return [ "status"=>0, "message"=> $ex->getMessage() ];
            //die($ex->getMessage());
            //return null;
        }
    }

    public static function GoogleUpload($file_upload_id, $file_name, $file_location, $new_location = "files"){
        $settings = static::GoogleStorageSettings();
        if(!$settings){
            abort(403, "Settings invalidated.");
            //return;
        }
        else if($settings['status'] == 0){
            abort(403, $settings['message']);
        }

        if(!$settings['bucket']){
            abort(403, "bucket not found");
            //return;
        }
        //Create Create clouddata here
        $cloudData = \App\Models\CloudData::create([
            "service_name"=>"google-cloud-storage",
            "file_upload_id"=>$file_upload_id,
            "file_allocation"=>$new_location,
            "file_name"=>$file_name,
            "is_uploaded"=>0
        ]);

        if(!Storage::disk('local')->exists($file_location."/".$file_name)){
            $cloudData->error_infos = "Does not found";
            $cloudData->save();
            die("Not Found".$file_location."/".$file_name);
            return;
        }

        $file = Storage::disk('local')->get($file_location."/".$file_name);
        try{
            $object = $settings['bucket']->upload($file, [
                'name' => $new_location."/".$file_name,
            ]);
            $cloudData->backup_infos = json_encode( $object );
            $cloudData->is_uploaded = 1;
        }catch(\Exception $ex){
            var_dump($ex->getMessage());
            $cloudData->error_infos = json_encode($ex);
            $cloudData->save();
            die();
            return;
        }
        $cloudData->save();
        return ["status"=>1];  

    }

    public static function GoogleFileUploadAttempt(){
        
        //Having interval to make sure that the file already uploaded.
        $id = FileUpload::whereRaw('  updated_at < NOW() - INTERVAL 5 MINUTE AND id NOT IN( select file_upload_id FROM cloud_data WHERE service_name = \'google-cloud-storage\') ')->first();
        if($id){
            $file_name = $id->target_id."_".$id->id.".".$id->file_ext;
            echo $file_name;
            
            static::GoogleUpload($id->id, $file_name, "images");
        }

    }

    public static function generateMySqlDump()
    {
        // Define the path where you want to save the dump file.
        $date = date('Y-m-d H-i');
        $file_name = "mysqldump-$date.sql";
        $dumpPath = storage_path("app/mysql-dump/$file_name");
        $username = env('DB_USERNAME');
        $password = str_replace("'","\\'", env('DB_PASSWORD'));
        $database = env('DB_DATABASE');
        // Replace 'your_database_name', 'your_username', and 'your_password' with your MySQL credentials.
        $command = "mysqldump -u $username -p'$password' $database > \"$dumpPath\"";

        // Run the mysqldump command.
        //echo $command;
        exec($command);
        $result = static::GoogleUpload(-1, $file_name, "mysql-dump","mysql-dump");
        if($result['status'] != 1)
            return;
        $files = Storage::disk("local")->allFiles("mysql-dump");
            
        $counter = count($files);
        if($counter <= 6) 
            return;
        foreach ($files as $file) {
            if($counter <=6)
                break;

            $time = Storage::disk('local')->lastModified($file);
            $fileModifiedDateTime = Carbon::parse($time);
                
            if (Carbon::now()->gt($fileModifiedDateTime->addHour(24))) {                            
                Storage::disk("local")->delete($file);
            }                
            //storage symbolic link files not required to delete.Still providing here for you reference
            //if (File::exists(public_path('storage/' . $file))) {
            //    File::delete(public_path('storage/' . $file));
            //}
            $counter--;
        } 
        
    }

    public static function getFileGoogleCloudStorage($file_name, $location = "files"){
 
        try{
            $settings = static::GoogleStorageSettings();
            if(!$settings){
                abort(404);
            }
            else if(!$settings['bucket']){
                abort(403, '');
            }
            $bucket = $settings['bucket'];
            //return null;
            $object = $bucket->object($location."/".$file_name);
            if ($object->exists()) {
                return $object;
            }

        }catch(NotFoundException $ex1) {
            // Handle the case where the object does not exist
            //abort(403, $ex1->getMessage());
            return null;
            //return "Object '$objectName' not found: " . $e->getMessage();
        }catch(GoogleException $ex2){            
            //abort(403, $ex1->getMessage());
            //var_dump($ex2);
            return null;
        }catch(\Exception $ex){
            //abort(403, $ex->getMessage());
            return null;
        }
        return null;

    }
   
    public static function getCloudFileUploads($file_upload_id, $storage="google-cloud"){
        $fileUpload = FileUpload::withTrashed()->find($file_upload_id);
        if(!$fileUpload)
            return null;
        if($storage == "google-cloud"){
            $file_name = $fileUpload->target_id."_".$fileUpload->id.".".$fileUpload->file_ext;
            $object = static::getFileGoogleCloudStorage($file_name);
            if(!$object){
                return null;
            }
            return [ "object" => $object, "filename"=> $fileUpload->file_name ];
        }
        return null;
    }



    //GETTING DATA FROM A TABLE
    public static function suggestion( Request $request, $suggestion_id)
    {
        $resume_docs = $request->file('attachments');
        //return count($resume_docs);
        $has_upload = false;

        foreach($resume_docs as $doc)
        {
            $has_upload = true;
            
            $doc_data =  FileUpload::create(
                [
                    "target_name_id" => 1,// Suggestion Attachment
                    "target_id" => $suggestion_id,
                    "file_name" => $doc->getClientOriginalName(),
                    "file_ext" => $doc->getClientOriginalExtension()
                ]
            );
            Storage::disk('local')->putFileAs(
                'attachment/files',
                $doc,
                $doc_data->id.".".$doc_data->file_ext
            );
            //$doc->store('users/' . $this->user->id . '/messages');
        }
        return $has_upload;
    }

}
