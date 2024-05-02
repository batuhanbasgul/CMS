<?php

namespace App\Http\Controllers;

use App\Models\Mail;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class ContactUsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
         * User Preferences Sessions for language,
         */
        if(!session('lang_code')){
            session()->forget('lang_code');
            session()->put('lang_code', App::getLocale());
        }else{
            App::setLocale(session('lang_code'));
        }
        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * Gönderilen mail bilgilerini ve içeriğini veritabanı kaydet.
         */
        //validator($request->input());
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'subject' => 'required|string|max:255',
            'context' => 'required|string|max:2500',
            '_token' => 'required'
        ]);
        $mail = new Mail();
        $mail->name = $request->name;
        $mail->email = $request->email;
        $mail->subject = $request->subject;
        $mail->context = $request->context;
        $mail->save();

        /**
         * Fetching contact email
         */
        $contactInfo = Contact::where('lang_code',session('lang_code'))->first();
        if($contactInfo){
            /** ►►►►► DEVELOPER ◄◄◄◄◄
             * Mail gönderme
             */
        \Mail::send('mail', [
            'name' => $request->name,
            'email' => $request->email,
            'subject' => $request->subject,
            'context' => $request->context,
        ], function ($message) use ($request) {
            $contactInfo = Contact::where('lang_code',session('lang_code'))->first();
            $message->from($request->email, $request->name);
            $message->to($contactInfo->email, $request->name)->subject($request->subject);
        });
        }
        return back()->with('success', 'Thanks for Contacting');
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
        //
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
