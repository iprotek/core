<?php

namespace iProtek\Core\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use iProtek\Core\Models\Package;
use iProtek\Core\Models\ResortService;
use iProtek\Core\Models\Customer;

class ListController extends Controller
{
    //
    public function suite_types(Request $request){
      $suite_types = \App\Models\SuiteType::with(['details', 'default_price', 'file_uploads'=>function($q){
         $q->whereRaw('deleted_at IS NULL');
      }]);

      return $suite_types->get();

      //return DB::select(' SELECT id, `name`, `description` from suite_types');
    }
    public function services(Request $request){
      $services = ResortService::where('type','service');
      $services->select('id', 'name as text', 'price');
      return $services->get();
       //return DB::select(' SELECT id, `name` as `text`, price from resort_services where `type`="service"');
    }
    public function amenities(Request $request){
      $amenities = ResortService::where('type','amenity');
      $amenities->select('id', 'name as text', 'price');
      return $amenities->get();
      //return DB::select(' SELECT id, `name` as `text`, price from resort_services where `type`="amenity"');
    }

    public function rooms(Request $request){
      $rooms = \App\Models\Room::with(['file_uploads'=>function($q){
         $q->orderBy('is_default','DESC');
      },'details'])->select('id', 'name', 'name as text', 'suite_type_id', 'description')->where('is_active',  1)->where('is_active',1)->get();

      return $rooms;
    }
    public function items(Request $request){
      $search_text = $request->search_text;
      $search_text = str_replace(' ', '%', $search_text);
      $items = \App\Models\Item::whereRaw(' name like ? ', ['%'.$search_text.'%'])->select('id', 'name', 'name as text', 'price');
      return $items->paginate(5);
    }
    public function payment_methods(Request $request){
      $search_text = $request->search_text;
      $search_text = str_replace(' ', '%', $search_text);
      $items = \App\Models\PaymentMethod::whereRaw(' name like ? ', ['%'.$search_text.'%'])->select('id', 'name', 'name as text');
      return $items->paginate(5);
    }

    public function packages(Request $request){
      $packages = Package::with(['file_uploads'=>function($q){
         $q->orderBy('is_default','DESC');
      },'list','amenities'=>function($q){
         $q->with(['resort_service'=>function($q){
            $q->with(['images']);
         }]);
      },'services'=>function($q){
         $q->with(['resort_service'=>function($q){
            $q->with(['images']);
         }]);
      }])->where('is_package', 1);
      return $packages->get();
    }

    public function packages_select2(Request $request){
      $search_text = $request->search_text;
      $search_text = str_replace(' ', '%', $search_text);
      $items = \App\Models\Package::whereRaw(' name like ? ', ['%'.$search_text.'%'])->select('id', 'name', 'name as text');
      return $items->paginate(5);
    }

    public function customers_select2(Request $request){
      if(!auth()->check()){
         return abort(403, 'Invalidated Request');
      }
      $search_text = $request->search_text;
      $search_text = str_replace(' ', '%', $search_text);
      $items = \App\Models\Customer::whereRaw(' CONCAT(name,email) like ? ', ['%'.$search_text.'%'])->select('id', 'name', 'name as text', 'email', 'first_name', 'last_name');
      return $items->paginate(5);
    }

    public function resort_services(Request $request){
      $services = ResortService::with(['images'=>function($q){
         $q->where('target_name','services')->orderBy('is_default','DESC');
      }])->where('type','service')->whereNotIn('name', ["bar","restaurant", "day use", "night use"]);
      return $services->get();
    }

    public function resort_amenities(Request $request){
      $amenities = ResortService::with(['images'=>function($q){
         $q->where('target_name','services')->orderBy('is_default','DESC');
      }])->where('type','amenity')->whereNotIn('name', ["bar","restaurant", "day use", "night use"]);
      return $amenities->get();
    }

    public function events_schedules(Request $request){
      return \App\Helpers\ResortEventHelper::getSchedules($request->from, $request->to, true);
    }
}
