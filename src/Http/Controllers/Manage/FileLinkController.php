<?php

namespace iProtek\Core\Http\Controllers\Manage;

use iProtek\Core\Http\Controllers\_Common\_CommonController;
use Illuminate\Http\Request;
use iProtek\Core\Models\FileLink;

class FileLinkController extends _CommonController
{
    
    //
    public $guard = 'admin';
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(Request $request){

        return $this->view('manage.file-links.index');
    }
    
    public function list(Request $request, $id = null){

        $data = FileLink::on();
        if($id){
           return $data->where('id', $id)->first();
        }

        return $data->paginate(10);

    }
    public function add(Request $request){
        
        $req = $request; 
        $this->validate($request, [
            "name"=>"required|unique:packages,name" , 
            "url"=>'required',
            "type"=>"required"
        ]); 

        $add = FileLink::create([
            "name" => $req->name,
            "url"  => $req->url,
            "file_type" => $req->type,
            "created_by" => auth()->user()->id
        ]);

        return ["status"=>1, "data"=>$add, "message"=>"Successfully Added."];

    }
    public function edit(Request $request, FileLink $id ){
        
        $req = $request;
        $this->validate($request, [
            "name"=>"required|unique:file_links,name,".$id->id, 
            "url"=>'required',
            "file_type"=>"required"
        ]);

        $id->update([
            "name"          => $req->name,
            "url"           => $req->url,
            "type"          => $req->type,
            "updated_by"    => auth()->user()->id
        ]);

        //Update List
        PackageList::where('package_id', $id->id)->delete();
        foreach($req->desc_list as $item){
            PackageList::create([
                "name"=>$item,
                "package_id"=>$id->id,
                "description"=>"N/A",
                "color"=>" ",
                "created_by"=>auth()->user()->id
            ]);
        }

        //CLEARING PACKAGE SERVICES
        PackageService::where('package_id', $id->id)->delete();

        //Update Services
        foreach($req->service_ids as $service_id){
            PackageService::create([
                "name"=>"N/A",
                "package_id"=>$id->id,
                "service_id"=>$service_id,
                "created_by"=>auth()->user()->id
            ]);
        }

        //Update Amenities
        foreach($req->amenity_ids as $amenity_id){
            PackageService::create([
                "name"=>"N/A",
                "package_id"=>$id->id,
                "service_id"=>$amenity_id,
                "created_by"=>auth()->user()->id
            ]);
        }


        return ["status"=>1, "data"=>$id, "message"=>"Updated."];

    }
}
