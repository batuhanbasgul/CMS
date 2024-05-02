<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\App;

class MailController extends Controller
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
     * Show the application dashboard.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
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

        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * Sidebar selected item
         */
        session()->forget('selectedSideMenu');
        session()->put('selectedSideMenu',last(explode('/',URL::current())));
        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * Fetch data in order to filter; read, unread, all mails.
         * Route to mails page with filtered data(mails) and and unread mails(unread_count) parameters.
         */
        if ('read' == $request->filter) {
            $response = Mail::where('is_read', 1)->orderBy('created_at', 'desc')->get();
        } else if ('unread' == $request->filter) {
            $response = Mail::where('is_read', 0)->orderBy('created_at', 'desc')->get();
        } else {
            $response = Mail::orderBy('created_at', 'desc')->get();
        }
        return view('admin.mail-box', [
            'mails' => $response,
            'read_count' => Mail::where('is_read', 1)->count(),
            'unread_count' => Mail::where('is_read', 0)->count(),
            'mail_count' => Mail::all()->count(),]);
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
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

        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * If user is not master, Sets Mail's is_read property to readed.
         */
        $mail = Mail::findOrFail($id);
        if (!Gate::allows('master')){
            $mail->is_read = 1;
            session()->forget('unread_mails');
            session()->put('unread_mails', Mail::where('is_read', 0)->orderBy('created_at', 'desc')->get()->count());
            $mail->save();
        }
        return redirect()->route('admin.index', ['mail_refresh' => true, 'mail' => $mail]);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
        $mail = Mail::findOrFail($id);
        if($mail->delete()){
            session()->flash('success', 'Silindi');
            if($request->turn_index){
                return redirect()->route('admin.mail-box.index');
            }
        }else{
            session()->flash('error', 'Silinemedi');
        }
        return back();
    }
}
