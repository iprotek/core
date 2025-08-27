<?php

namespace iProtek\Core\Http\Controllers\Manage;

use Illuminate\Http\Request;
use iProtek\Core\Http\Controllers\_Common\_CommonController;
use iProtek\Core\Rules\IntegerOnly;
use iProtek\Core\Helpers\AppVarHelper;
class CompanyDetailsController extends _CommonController
{
    //
    public $guard = 'admin';
    public function __construct()
    {
        //$this->middleware('auth');
    }
    public function index(Request $request){

        return $this->view('iprotek_core::company-details.index');
    }

    public function update_profile(Request $request){
       //return \iProtek\Core\Helpers\AppVarHelper::set(" buSiness_name", "Marigold 4");
         
        AppVarHelper::set([
            "business_name"=>$request->business_name,
            "business_address"=>$request->business_address,
            "business_tin"=>$request->business_tin,
            "business_mobile"=>$request->business_mobile,
            "business_representative"=>$request->business_representative,
            "business_tel"=>$request->business_tel,
            "business_email"=>$request->business_email,
            "business_facebook_link"=>$request->business_facebook_link,
            "business_linkedin_link"=>$request->business_linkedin_link,
            "business_twitter_link"=>$request->business_twitter_link,
            "business_snapchat_link"=>$request->business_snapchat_link,
            "business_youtube_link"=>$request->business_youtube_link,
            "booking_airbnb_link"=>$request->booking_airbnb_link,
            "booking_link"=>$request->booking_link,
            "booking_vrbo_link"=>$request->booking_vrbo_link,
            "booking_expedia_link"=>$request->booking_expedia_link
        ]);
         
        return ["status"=>1,"message"=>"Updated"];
    }
    public function get_profile(Request $request){
       return AppVarHelper::get([
        "business_name",
        "business_address",
        "business_tin",
        "business_mobile",
        "business_representative",
        "business_tel",
        "business_email",
        "business_facebook_link",
        "business_linkedin_link",
        "business_twitter_link",
        "business_snapchat_link",
        "business_youtube_link",
        "booking_airbnb_link",
        "booking_link",
        "booking_vrbo_link",
        "booking_expedia_link"
        ]);
    }
    public function update_background_setting(Request $request){    
        
        if($request->allow_shuffle == 1){
            $this->validate($request, [
                "shuffle_duration"=>'required|integer|min:10'//, new IntegerOnly ] 
            ]);
        }
        AppVarHelper::set(["allow_shuffle"=> ($request->allow_shuffle == 1 ? 1:0), "shuffle_duration"=> $request->shuffle_duration ]);

        return ["status"=>1, "message"=>"Background Settings Updated."];
    }
    public function get_background_setting(Request $request){
        
       return AppVarHelper::get([
        "shuffle_duration",
        "allow_shuffle"
        ]);
    }

    public function update_logo_setting(Request $request){
        $file_upload = \iProtek\Core\Models\FileUpload::where(['target_id'=>1,"target_name"=>"business_logos"])->orderBy('is_default','DESC')->first();
        
        $business_logo_url = "";
        $business_logo_type = "";
        if($file_upload){
            $business_logo_url = $file_upload->public_link;
            $business_logo_type = $file_upload->file_type;
        }
        AppVarHelper::set([
            "business_logo_url"  => $business_logo_url,
            "business_logo_type" => $business_logo_type
        ]);

        return ["status"=>1, "message"=>"Logo updated"];
    }

    public function update_terms_and_conditions(Request $request){
        \iProtek\Core\Helpers\CompanyDetailsHelper::set_terms_and_condition($request->html);
        return ["status"=>1, "message"=>"Terms and conditions Updated."];
    }

    public function get_terms_and_conditions(Request $request){

        return [
            "html" => \iProtek\Core\Helpers\CompanyDetailsHelper::get_terms_and_conditions()
        ];

    }

    public function update_contact_us(Request $request){

        \iProtek\Core\Helpers\CompanyDetailsHelper::set_contact_us($request->html);
        return ["status"=>1, "message"=>"Terms and conditions Updated."];
    
    }

    public function get_contact_us(Request $request){

        return [
            "html" => \iProtek\Core\Helpers\CompanyDetailsHelper::get_contact_us()
        ];

    }

    public function update_logo_link(Request $request){
        $url = $request->link; //business_logo_url
        $logo_type = $request->logo_type;
        
        AppVarHelper::set([
            "business_logo_url"  => $url,
            "business_logo_type" => $logo_type
        ]);
        return ["status"=>1, "message"=>"Logo has been updated.". $logo_type];
    }

    public function update_theme(Request $request){
        $this->validate($request, [
            "theme_dashboard_nav_color"=>'required|string|max:50',
            "theme_dashboard_sidebar_color"=>'required|string|max:50',
            "theme_dashboard_card_color"=>'nullable|string|max:50',
            "theme_front_nav_color"=>'nullable|string|max:50',
            "theme_front_card_color"=>'nullable|string|max:50'
        ]);

         $user_id = auth()->user()->id;
        //TODO:: Set for specific user not for general
        
        AppVarHelper::set([
            "theme_dashboard_nav_color_$user_id" => $request->theme_dashboard_nav_color,
            "theme_dashboard_sidebar_color_$user_id" => $request->theme_dashboard_sidebar_color,
            "theme_dashboard_card_color_$user_id" => $request->theme_dashboard_card_color ?:"",
            "theme_front_nav_color_$user_id" => $request->theme_front_nav_color ?:"",
            "theme_front_card_color_$user_id" => $request->theme_front_card_color ?:""
        ]);

        return ["status"=>1, "message"=>"Theme updated."];
    }

    public function get_theme(Request $request){

        $data = \iProtek\Core\Helpers\UISettingHelper::get_theme();
        return $data;
    }

    
    public function reset_theme(Request $request){

        $data = \iProtek\Core\Helpers\UISettingHelper::reset_theme();

        return ["status"=>1, "message"=>"Theme resetted."];
    
    }

}
