<?php

namespace iProtek\Core\Http\Controllers;

use Illuminate\Http\Request;
use iProtek\Core\Http\Controllers\_Common\_CommonController;
use Response;
use DB;
use iProtek\Core\Models\LanguageList;
use iProtek\Core\Models\LanguageEnToAny;
use iProtek\Core\Models\LanguageTranslation;
use iProtek\Core\Models\LanguageWord;
use iProtek\Core\Helpers\LanguageHelper;

class LanguagesController extends _CommonController
{
    //
    protected $guard = 'admin';

    public function languages_page()
    {
        $infos = $this->common_infos();
        $infos['languages'] = LanguageList::orderBy('id','ASC')->get();
        return view($this->guard.'.languages', $infos);
    }

    public function get_languages(){
        return [  "list"=> LanguageList::get(), "lang"=> LanguageHelper::get_language() ];
    }
    public function set_current_language($language){
       return LanguageHelper::set_language($language);   
    }
    public function get_current_language(){
        return LanguageHelper::get_language();   
    }
    public function set_mode($mode){
        LanguageHelper::set_mode($mode);
       return ["RetVal"=>1]; 
    }
    public function get_mode(){
       return  [
           "mode"=> LanguageHelper::get_mode() ?: 'none'
        ];
    }

    public function get_translation(Request $request){

        //return $request->label_trans_ids;
        //LanguageEnToAny::where('', $request->label_trans_ids)->select('')
        
        //GET SESSION LANGUAGE

        $result =  DB::connection('mysql_translation')->table('language_en_to_anies')->selectRaw(' id, label_trans_id, word_id, settings,  language_get_info(label_trans_id) as translations')->whereIn('label_trans_id', $request->label_trans_ids )->get();
        $default = DB::connection('mysql_translation')->select(' SELECT 0 as id, "default" as label_trans_id, 0 as word_id, null as settings,language_get_info("default") as translations')[0];



        //lang is the default language set
        //grammars is the languages set by the admin
        //default is the default set of languages with Translations
        //mode is the edit, none, clearing
        $lang = LanguageHelper::get_language();
        $mode = LanguageHelper::get_mode();
        return [ "lang"=>$lang, "grammars"=> $result, 'default'=>$default, 'mode'=>$mode ];

    }

    public function languages_update(Request $request, $language_trans_id){

        $user = auth('admin')->user();
        if($user == null)
            return null;

        $grammars = $request->grammars;// {"en":"Hi", "vi":"ZFDSD"} 
        if( empty( $grammars['en'] ) )
            abort('403', 'English is required');

        $grammarList = []; //lang_id, lang_abbr, word, word_id
        $languages = LanguageList::get();

        foreach($languages as $language){
            if(!empty($grammars[$language->code]))
                array_push( $grammarList, [ "lang_id"=> $language->id, "lang_abbr"=> $language->code, "word"=> $grammars[$language->code] ]  );
        }

        //GET THE FIRST GRAMMAR
        $english_index = null;
        $settings = [];

        //INSERT THESE WORDS AND GET THEIR IDS.
        for( $i = 0; $i < count( $grammarList); $i++){
            if( !empty( trim( $grammarList[$i]['word'] ))){

               $lang_word = LanguageWord::where('words', $grammarList[$i]['word'])->first();
               if($lang_word == null){
                   $lang_word = LanguageWord::create(['words'=>$grammarList[$i]['word']]);
               }
               $grammarList[$i]['word_id'] = $lang_word->id;
               if($grammarList[$i]['lang_abbr'] == 'en')
                    $english_index = $i;

            
                //Use for EN TO ANIES
                $settings[$grammarList[$i]['lang_abbr']] = $lang_word->id;
            
            }
        }

        //ADD TO LANGUAGE TRANSLATIONS
        foreach($grammarList as $grammar){

            $has_translaton = LanguageTranslation::where([
                'from_lang_id'=> $grammarList[$english_index]['lang_id'],
                'from_word_id'=>$grammarList[$english_index]['word_id'],
                'to_lang_id'=>$grammar['lang_id'],
                'to_word_id'=>$grammar['word_id']
            ])->first();
            if($has_translaton == null){
                $has_translaton =LanguageTranslation::create([
                    'from_lang_id'=> $grammarList[$english_index]['lang_id'],
                    'from_word_id'=>$grammarList[$english_index]['word_id'],
                    'to_lang_id'=>$grammar['lang_id'],
                    'to_word_id'=>$grammar['word_id']
                ]);
            }
            //array_push($settings, [ $grammar['lang_abbr']=>$has_translaton->id  ]);
        }

        //ADD OR UPDATE EN TO ANIES
        $lang_entoany = LanguageEnToAny::where(['label_trans_id'=> $language_trans_id])->first();
        if($lang_entoany == null){
            $lang_entoany = LanguageEnToAny::create([
                'label_trans_id'=> $language_trans_id, 
                'word_id'=>$grammarList[$english_index]['word_id'],
                'settings'=> json_encode($settings)
            ]);
        }
        else{
            //'word_id'=>$grammarList[$english_index]['word_id']
            $lang_entoany->word_id = $grammarList[$english_index]['word_id'];
            $lang_entoany->settings = json_encode($settings);
            $lang_entoany->save();
        }

        //
        $lang = LanguageHelper::get_language();
        //$mode = LanguageHelper::get_mode();
        $result =  DB::connection('mysql_translation')->table('language_en_to_anies')->selectRaw(' id, label_trans_id, word_id, settings,  language_get_info(label_trans_id) as translations')->whereIn('label_trans_id', [$language_trans_id] )->first();
        return [ "lang"=>$lang, "grammar"=> $result ];
    }
}
