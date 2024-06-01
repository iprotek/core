<?php

namespace iProtek\Core\Http\Controllers\Manage;

use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Validator;
use iProtek\Core\Http\Controllers\_Common\_CommonController;
use Illuminate\Routing\Controller;
use iProtek\Core\Models\ResortEvent;
use DB;
use iProtek\Core\Models\Booking;
use iProtek\Core\Models\BookingBundle;
use iProtek\Core\Helpers\PayHttp;
use iProtek\Core\Helpers\PayModelHelper;

class DashboardController extends _CommonController
{
    //
    public $guard = 'admin';
    public function __construct()
    {
        //$this->middleware('auth');
    }

    public function get_stat(Request $request){
        $user = $request->get('user');
        $group_id = PayModelHelper::get_group_id( $user, false);
        $data = DB::select("CALL spDashboardStat(?)",[$group_id]);
        return $data[0];
    }


    public function index(Request $request){

        return $this->view('manage.dashboard.index');
    }

    public function pay_acccount(Request $request){

        return PayHttp::account_info();

    }

    public function calendar_schedules(Request $request){
        
        $from = $request->from;
        $to = $request->to;

        $schedules = [];


        //Events should process here... 
        $events = ResortEvent::on(); 
        //$events->where('is_active', 1);
        $events->orderBy('from','ASC');
        if($from && !$to){
            $events = $events->whereRaw('date(`from`) = date(?)', [$from])->get();
        }
        else if($from && $to){            
            //$events->whereRaw(' date(?) <= date(`from`) AND date(`from`) <= date(?)', [$from, $to]);            
            //$events->whereRaw(' ((date(`from`) <= date(?) AND date(?) <= date(`to`)) OR (date(`from`) <= date(?) AND date(?) <= date(`to`)) OR (date(?) <= date(`from`) AND date(`to`) <= date(?)) )', [$from, $from , $to, $to, $from, $to]);
            $events->orWhereRaw(' date(`from`) <= date(?) AND date(?) <= date(`to`)', [$from, $from]);
            $events->orWhereRaw(' date(`from`) <= date(?) AND date(?) <= date(`to`)', [$to, $to]);
            $events->orWhereRaw(' date(?) <= date(`from`) AND date(`to`) <= date(?) ', [$from, $to]);
            $events = $events->get();
        }
        //Add to listing
        foreach($events as $event){
            if($event->is_active != 0){
                $schedules[] = [
                    "id"=>$event->id,
                    "title"=>$event->name,
                    "start"=>$event->from,
                    "end"=>$event->to,
                    "color"=>$event->color,
                    "type"=>"event"
                ];
            }
        }


        //Booking should process here..
        $roomBooking = Booking::on();
        $roomBooking->orderBy('date_from','ASC');
        if($from && !$to){
            $roomBooking->select( DB::raw('count(room_id) as room_count'), 'date_from', 'date_to');
            $roomBooking = $roomBooking->whereRaw('date(`date_from`) = date(?)', [$from])->get();
        }
        else if($from && $to){            
            //$events->whereRaw(' date(?) <= date(`from`) AND date(`from`) <= date(?)', [$from, $to]);
            $roomBooking->orWhereRaw(' date(`date_from`) <= date(?) AND date(?) <= date(`date_to`)', [$from, $from]);
            $roomBooking->orWhereRaw(' date(`date_from`) <= date(?) AND date(?) <= date(`date_to`)', [$to, $to]);
            $roomBooking->orWhereRaw(' date(?) <= date(`date_from`) AND date(`date_to`) <= date(?) ', [$from, $to]);
            $roomBooking->select( DB::raw('count(room_id) as room_count'), 'date_from', 'date_to');
            $roomBooking->groupBy('date_from', 'date_to');
            $roomBooking = $roomBooking->get();
        }

        $book_count = 0;
        foreach($roomBooking as $booked){
            $schedules[] = [
                "id"=>"bk".$book_count,
                "title"=>'('.$booked->room_count.') - Room'.($booked->room_count > 1 ?'s':''),
                "start"=>$booked->date_from,
                "end"=>$booked->date_to,
                "color"=>"success",
                "type"=>"room"
            ];
            $book_count++;
        }


        //Package should also be here..
        $bookingBundle = BookingBundle::on();
        $bookingBundle->orderBy('date_from','ASC'); 
        $bookingBundle->whereRaw(' package_id > 0 AND ( (date(`date_from`) <= date(?) AND date(?) <= date(`date_to`)) OR ( date(`date_from`) <= date(?) AND date(?) <= date(`date_to`) ) OR ( date(?) <= date(`date_from`) AND date(`date_to`) <= date(?) ) )', [$from, $from, $to, $to, $from, $to ]);
        $bookingBundle->select( DB::raw('count(package_id) as package_count'), 'date_from', 'date_to');
        $bookingBundle->groupBy('date_from', 'date_to');
        $bookingBundle = $bookingBundle->get();

        $package_count = 0;
        foreach($bookingBundle as $packageItem){
            $schedules[] = [
                "id"=>"pk".$package_count,
                "title"=>'('.$packageItem->package_count.') - Package'.($packageItem->package_count > 1 ?'s':''),
                "start"=>$packageItem->date_from,
                "end"=>$packageItem->date_to,
                "color"=>"danger",
                "type"=>"package"
            ];
            $package_count++;
        }



        return $schedules;


    }

    public function schedules(Request $request){
        $from = $request->from;
        $to = $request->to;

        $schedules = [];


        //Events should process here... 
        $schedules = \iProtek\Core\Helpers\ResortEventHelper::getSchedules($from, $to);
        /*
        $events = ResortEvent::on(); 
        $events->orderBy('from','ASC');
        if($from && !$to){
            $events = $events->whereRaw(' date(`from`) = date(?)', [$from])->get();
        }
        else if($from && $to){            
            //$events->whereRaw(' date(?) <= date(`from`) AND date(`from`) <= date(?)', [$from, $to]);
            $events->orWhereRaw(' date(`from`) <= date(?) AND date(?) <= date(`to`)', [$from, $from]);
            $events->orWhereRaw(' date(`from`) <= date(?) AND date(?) <= date(`to`)', [$to, $to]);
            $events->orWhereRaw(' date(?) <= date(`from`) AND date(`to`) <= date(?) ', [$from, $to]);
            $events = $events->get();
        }
        //Add to listing
        foreach($events as $event){
            $schedules[] = [
                "id"=>$event->id,
                "title"=>$event->name,
                "start"=>substr($event->from, 0, 16),
                "end"=>substr($event->to, 0, 16),
                "color"=>$event->color,
                "type"=>"event"
            ];
        }
        */


        //Booking should process here..
        $bookings = \iProtek\Core\Models\Booking::with(['room']);
        
        if($from && !$to){
            $bookings = $bookings->whereRaw('date(`date_from`) = date(?)', [$from])->get();
        }
        else{
            
            $bookings->orWhereRaw(' date(`date_from`) <= date(?) AND date(?) <= date(`date_to`)', [$from, $from]);
            $bookings->orWhereRaw(' date(`date_from`) <= date(?) AND date(?) <= date(`date_to`)', [$to, $to]);
            $bookings->orWhereRaw(' date(?) <= date(`date_from`) AND date(`date_to`) <= date(?) ', [$from, $to]);
            $bookings = $bookings->get();

        }

        foreach($bookings as $book){
            if($book->room){
                $schedules[] = [
                    "id"=>$book->id,
                    "title"=>$book->room->name,
                    "start"=>substr($book->date_from, 0, 16),
                    "end"=>substr($book->date_to, 0 , 16),
                    "color"=>"primary",
                    "type"=>"booking",
                    "booking_bundle_id"=>$book->booking_bundle_id
                ];
            }
        }

        //Booking should process here..
        $bookingBundles = BookingBundle::with(['package']);
        
        if($from && !$to){
            $bookingBundles = $bookings->whereRaw(' package_id > 0 ANDdate(`date_from`) = date(?)', [$from])->get();
        }
        else{
            
            $bookingBundles->whereRaw(' package_id > 0 AND ( (date(`date_from`) <= date(?) AND date(?) <= date(`date_to`)) OR ( date(`date_from`) <= date(?) AND date(?) <= date(`date_to`) ) OR ( date(?) <= date(`date_from`) AND date(`date_to`) <= date(?) ) )', [$from, $from, $to, $to, $from, $to ]);
            $bookingBundles = $bookingBundles->get();

        }

        foreach($bookingBundles as $bundle){
            if($bundle->package){
                $schedules[] = [
                    "id"=>$bundle->id,
                    "title"=>$bundle->package->name,
                    "start"=>substr($bundle->date_from,0, 16),
                    "end"=>substr($bundle->date_to, 0, 16),
                    "color"=>"danger",
                    "type"=>"package",
                    "booking_bundle_id"=>$bundle->id
                ];
            }
        }

        return $schedules;
    }



}
