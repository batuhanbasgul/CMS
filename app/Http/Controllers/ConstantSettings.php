<?php

namespace App\Http\Controllers;

use App\Models\Constant;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\App;

class ConstantSettings extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * Sidebar selected item
         */
        session()->forget('selectedSideMenu');
        session()->put('selectedSideMenu',last(explode('/',URL::current())));

        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * User Preferences Sessions for language,
         */
        if(!session('lang_code')){
            session()->forget('lang_code');
            session()->put('lang_code', App::getLocale());
        }else{
            App::setLocale(session('lang_code'));
        }
        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * Works with lang_code parameter;
         * if null default is "tr"
         * if not null and no content on that language routes to "add" page.
         * if not null and there is content for that language, routes to edit page with lang_code, aboutUs, langs parameters.
         */
        if (0 == Constant::all()->count()) {
            /** ►►►►► DEVELOPER ◄◄◄◄◄
             * Language parameter validations.
             */
            $request->validate([
                'lang_code' => 'required|alpha|min:2|max:3',
            ]);
            $allLanguages = Language::get();
            $isLanguageExist = false;
            foreach($allLanguages as $item){
                if($item->lang_code == $request->lang_code){
                    $isLanguageExist = true;
                }
            }
            if(!$isLanguageExist){
                $request->session()->flash('error', 'Kayıt Başarısız');
                return back();
            }

            $constant = new Constant(); //Auto Generate
            $constant->lang_code = session('lang_code');
            $constant->save();
            return view('admin.constant-settings-update', [
                'lang_code' => session('lang_code'),
                'content' => $constant,
                'langs' => Language::orderBy('order','asc')->get(),
            ]);
        } else {
            $constant = Constant::where('lang_code', $request->lang_code)->get();
            if (0 == $constant->count()) {
                $constant = new Constant(); //Auto Generate
                $constant->lang_code = $request->lang_code;
                $constant->save();
                return view('admin.constant-settings-update', [
                    'lang_code' => $request->lang_code,
                    'content' => $constant,
                    'langs' => Language::orderBy('order','asc')->get(),
                ]);
            }
            return view('admin.constant-settings-update', [
                'lang_code' => $request->lang_code,
                'content' => $constant[0],
                'langs' => Language::orderBy('order','asc')->get(),
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * Language parameter validations.
         */
        $request->validate([
            'lang_code' => 'required|alpha|min:2|max:3',
        ]);
        $allLanguages = Language::get();
        $isLanguageExist = false;
        foreach($allLanguages as $item){
            if($item->lang_code == $request->lang_code){
                $isLanguageExist = true;
            }
        }
        if(!$isLanguageExist){
            $request->session()->flash('error', 'Kayıt Başarısız');
            return back();
        }

        $constant = new Constant();
        $constant->lang_code = $request->lang_code;

        if ($constant->save()) {
            $request->session()->flash('success', 'Kayıt Başarılı');
        } else {
            $request->session()->flash('error', 'Kayıt Başarısız');
        }
        return redirect()->route('admin.constant-settings.index', [
            'lang_code' => $request->lang_code
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * Language parameter validations.
         */
        $request->validate([
            'lang_code' => 'required|alpha|min:2|max:3',
        ]);
        $allLanguages = Language::get();
        $isLanguageExist = false;
        foreach($allLanguages as $item){
            if($item->lang_code == $request->lang_code){
                $isLanguageExist = true;
            }
        }
        if(!$isLanguageExist){
            $request->session()->flash('error', 'Kayıt Başarısız');
            return back();
        }

        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * Call the data.
         */
        $constant = Constant::findOrFail($id);
        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * Six different forms.
         */
        if($request->has('updatepageconstant')){
            $constant->title = $request->title;
            $constant->subtitle = $request->subtitle;
            $constant->date = $request->date;
            $constant->author = $request->author;
            $constant->keywords = $request->keywords;
            $constant->read_more = $request->read_more;
            $constant->detail = $request->detail;
            $constant->watch_video = $request->watch_video;
            $constant->buy_title = $request->buy_title;
            $constant->buy_subtitle = $request->buy_subtitle;
            $constant->buy_price_button = $request->buy_price_button;
            $constant->buy_contact_button = $request->buy_contact_button;
            $constant->close_lang = $request->close_lang;
            session()->flash('tab_page','page_constant');    //Which tab will be opened.
        }else if($request->has('updatecontactconstant')){
            $constant->phone_1 = $request->phone_1;
            $constant->phone_2 = $request->phone_2;
            $constant->gsm_1 = $request->gsm_1;
            $constant->gsm_2 = $request->gsm_2;
            $constant->email_1 = $request->email_1;
            $constant->email_2 = $request->email_2;
            $constant->address_1 = $request->address_1;
            $constant->address_2 = $request->address_2;
            session()->flash('tab_page','contact_constant');    //Which tab will be opened.
        }else if($request->has('updatecontactformconstant')){
            $constant->contact_name = $request->contact_name;
            $constant->contact_mail = $request->contact_mail;
            $constant->contact_phone = $request->contact_phone;
            $constant->subject = $request->subject;
            $constant->message = $request->message;
            $constant->send_button = $request->send_button;
            $constant->sent_message_success = $request->sent_message_success;//
            $constant->sent_name_error = $request->sent_name_error;
            $constant->sent_mail_error = $request->sent_mail_error;
            $constant->sent_subject_error = $request->sent_subject_error;
            $constant->sent_message_error = $request->sent_message_error;
            $constant->sent_validation_error = $request->sent_validation_error;
            session()->flash('tab_page','contact_form_constant');    //Which tab will be opened.
        }else if($request->has('updateproductsconstant')){
            $constant->categories = $request->categories;
            $constant->all_products = $request->all_products;
            $constant->product_no = $request->product_no;
            $constant->product_info = $request->product_info;
            $constant->product_name = $request->product_name;
            $constant->product_price = $request->product_price;
            $constant->product_keywords = $request->product_keywords;
            $constant->no_product = $request->no_product;
            session()->flash('tab_page','products_constant');    //Which tab will be opened.
        }else if($request->has('updatefooterconstant')){
            $constant->quickmenu_title_1 = $request->quickmenu_title_1;
            $constant->quickmenu_title_2 = $request->quickmenu_title_2;
            $constant->copyright_description = $request->copyright_description;
            $constant->phone = $request->phone;
            $constant->email = $request->email;
            $constant->address = $request->address;
            session()->flash('tab_page','footer_constant');    //Which tab will be opened.
        }else if($request->has('updateacceptcookies')){
            $constant->cookie_title = $request->cookie_title;
            $constant->cookie_description = $request->cookie_description;
            $constant->cookie_button_refuse = $request->cookie_button_refuse;
            $constant->link_title = $request->link_title;
            $constant->cookie_button = $request->cookie_button;
            session()->flash('tab_page','accept_cookies_constant');    //Which tab will be opened.
        }

        if ($constant->save()) {
            $request->session()->flash('success', 'Kayıt Başarılı');
        } else {
            $request->session()->flash('error', 'Kayıt Başarısız');
        }
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
