<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Language;
use App\Models\AppCard;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\App;

class AppCardController extends Controller
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
        if($request->refresh){
            return back();
        }
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
         * if not null and no content on that language reroutes to "add" page.
         * if not null and there is content for that language, reroutes to edit page with lang_code, cards, langs parameters.
         */
        if ($request->lang_code) {
            return view('admin.app-card-settings', ['langs' => Language::orderBy('order','asc')->get(), 'cards' => AppCard::where('lang_code', $request->lang_code)->get(), 'lang_code' => $request->lang_code]);
        } else {
            return view('admin.app-card-settings', ['langs' => Language::orderBy('order','asc')->get(), 'cards' => AppCard::where('lang_code', session('lang_code'))->get(), 'lang_code' => session('lang_code')]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * User Preferences Sessions for language,
         */
        if(!session('lang_code')){
            session()->forget('lang_code');
            session()->put('lang_code', App::getLocale());
        }else{
            App::setLocale(session('lang_code'));
        }
        return view('admin.app-card-settings-add', ['lang_code' => $request->lang_code, 'langs' => Language::all()]);
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

        $card = new AppCard();
        $card->title = $request->title;
        $card->description = $request->description;
        $card->icon = $request->icon;

        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * Add to last.
         */
        $order = 0;
        foreach (AppCard::where('lang_code', $request->lang_code)->get() as $a) {
            if ($a->order > $order) {
                $order = $a->order;
            }
        }
        $card->order = $order + 1;

        $card->lang_code = $request->lang_code;
        if ($card->save()) {
            $request->session()->flash('success', 'Başarıyla oluşturuldu');
            return redirect()->route('admin.app-card-settings.index', ['lang_code' => $request->lang_code]);
        } else {
            $request->session()->flash('error', 'Kaydetme Başarısız');
            return redirect()->route('admin.app-card-settings.index', ['lang_code' => $request->lang_code]);
        }
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

    /** ►►►►► DEVELOPER ◄◄◄◄◄
     * Order management page.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editOrder(Request $request)
    {
        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * User Preferences Sessions for language,
         */
        if(!session('lang_code')){
            session()->forget('lang_code');
            session()->put('lang_code', App::getLocale());
        }else{
            App::setLocale(session('lang_code'));
        }
        $cards = AppCard::where('lang_code', $request->lang_code)->orderBy('order','asc')->get();
        if(count($cards) == 0){
            $request->session()->flash('no_card','Kart Kaydı Bulunamadı');
            return back();
        }
        return view('admin.app-card-settings-update-order', [
            'cards' => $cards,
            'langs' => Language::all()
        ]);
    }

    /** ►►►►► DEVELOPER ◄◄◄◄◄
     * ID Array from String Data.
     */
    public function dataFromSingleLevelNested($data_string){
        $tmp_str = '';
        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * removing "[,]".
         */
        for($i=1;$i<strlen($data_string)-1;$i++){
            $tmp_str=$tmp_str.$data_string[$i];
        }
        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * Splitting with ",".
         */
        $exploded_data = explode(',',$tmp_str);
        $clean_exploded_data = [];
        $tmp_str = '';
        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * Getting IDs from items.
         */
        foreach($exploded_data as $item){
            for($i=0;$i<strlen($item);$i++){
                if(
                $item[$i] == '0' ||$item[$i] == '1' ||$item[$i] == '2' ||$item[$i] == '3' ||$item[$i] == '4' ||
                $item[$i] == '5' ||$item[$i] == '6' ||$item[$i] == '7' ||$item[$i] == '8' ||$item[$i] == '9'
                ){
                    $tmp_str = $tmp_str.$item[$i];
                }
            }
            array_push($clean_exploded_data,$tmp_str);
            $tmp_str = '';
        }
        return $clean_exploded_data;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function edit($id, Request $request)
    {
        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * User Preferences Sessions for language,
         */
        if(!session('lang_code')){
            session()->forget('lang_code');
            session()->put('lang_code', App::getLocale());
        }else{
            App::setLocale(session('lang_code'));
        }

        $card = AppCard::findOrFail($id);
        $cards = AppCard::where('lang_code', $card->lang_code)->orderBy('order', 'asc')->get();
        return view('admin.app-card-settings-update', [
            'card' => $card,
            'cards' => $cards,
            'langs' => Language::all(),
            'request' => $request->page
        ]);
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
        if($request->has('updatecardsettings')){
            $card = AppCard::findOrFail($id);
            $card->title = $request->title;
            $card->description = $request->description;
            $card->icon = $request->icon;

            if ($card->save()) {
                $request->session()->flash('success', 'Başarıyla Güncellendi');
            } else {
                $request->session()->flash('error', 'Güncelleme Başarısız');
            }
            return back();
        }else if($request->has('updatecardsorder')){
            $sorted_ids = $this->dataFromSingleLevelNested($request->nestable_output);
            if($sorted_ids[0] == ''){
                $request->session()->flash('error', 'Başarısız');
                return back();
            }
            $counter = 1;
            foreach($sorted_ids as $id){
                $cardItem = AppCard::findOrFail($id);
                $cardItem->order = $counter;
                $cardItem->save();
                $counter++;
            }
            $request->session()->flash('success', 'Başarılı');
            return back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $card = AppCard::findOrFail($id);
        $lang_code = $card->lang_code;
        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * Managing order after deleting.
         */
        foreach (AppCard::where('lang_code', $card->lang_code)->where('order', '>', $card->order)->get() as $o_item) {
            $o_item->order = $o_item->order - 1;
            $o_item->save();
        }
        $card->delete();
        session()->flash('success', 'Deleted');
        return redirect()->route('admin.app-card-settings.index', ['lang_code' => $lang_code]);
    }
}
