<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use App\Services\FileManagerService;
use App\Models\ImageManager;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\App;

class UserSettingsController extends Controller
{
    /** ►►►►► DEVELOPER ◄◄◄◄◄
    * File Manager Service for upload, validations etc.
    *
    * @var \App\Services\FileManagerService
    */
    protected $fileManagerService;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(FileManagerService $fileManagerService)
    {
        $this->middleware('auth');
        $this->fileManagerService = $fileManagerService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
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
         * Master authority can see every user, admin can not see master users.(AuthServiceProvider.php)
         */
        if (Gate::allows('master')) {
            return view('admin.user-settings', ['users' => User::orderBy('id', 'asc')->get()]);
        }
        if (Gate::allows('admin')) {
            return view('admin.user-settings', ['users' => User::where('user_role', 'admin')->orderBy('id', 'asc')->get()]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
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

        return view('admin.user-settings-add');
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
         * Adding new user by mail and password, authority default is admin.
         */
        $request->validate([
            'name' => 'bail|string|max:100',
            'email' => 'bail|required|email',
            'password' => 'bail|required|min:8|max:16',
            'confirm_password' => 'bail|required|min:8|max:16']);

        if (request()->get('password') != request()->get('confirm_password')) {
            $request->session()->flash('password_error', 'Şifreler Eşleşmedi');
            return redirect()->route('admin.user-settings.create')->withInput();
        } else {
            foreach (User::all() as $user) {
                if ($request->email == $user->email) {
                    $request->session()->flash('email', 'E-posta kullanılıyor');
                    return redirect()->route('admin.user-settings.create')->withInput();
                }
            }
        }
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->title = $request->user_title;
        $user->password = Hash::make($request->password);
        if ($user->save()) {
            $request->session()->flash('success', 'Kullanıcı Bilgileri Eklendi');
        } else {
            $request->session()->flash('error', 'Kullanıcı Bilgileri Eklenemedi');
        }
        return redirect()->route('admin.user-settings.index');
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
         * If target user's authority level is higher than tha user, edit does not allowed.
         */
        if (Gate::allows('crud-show', User::findOrFail($id))) {
            return view('admin.user-settings-update', [
                'user' => User::find($id),
                'image_size' => ImageManager::where('type','kullanıcı_resim')->first()
            ]);
        } else {
            return redirect()->route('admin.index');
        }
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
         * If user has the authority for crud.
         */
        if (Gate::allows('crud-show', User::findOrFail($id))) {

            /** ►►►►► DEVELOPER ◄◄◄◄◄
             * Call the data.
             */
            $user = User::findOrFail($id);
            /** ►►►►► DEVELOPER ◄◄◄◄◄
             * Updating profile photo.
             */
            if($request->has('updateimagelarge')){
                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Validation for file mime from FileManagerService.
                 */
                if($request->hasFile('profile_image')){
                    if(!$this->fileManagerService->checkExtension($request->profile_image)){
                        $request->session()->flash('file_extension_error','Dosya uzantısı.');
                        return back();
                    }
                }
                /** ►►►►► DEVELOPER ◄◄◄◄◄
                 * Upload cropped image if there is one.
                 */
                if ($request->cropped_data) {
                    /** ►►►►► DEVELOPER ◄◄◄◄◄
                    * Quality, fize size, image sizes.
                    */
                    $imageManager = ImageManager::where('type', 'kullanıcı_resim')->first();
                    /** ►►►►► DEVELOPER ◄◄◄◄◄
                     * Uploading image from FileManagerService. Returns file path.
                     */
                    $result = $this->fileManagerService->uploadImage($user->name, $request->cropped_data, 'user', $imageManager);
                    if($result == '0'){
                        $request->session()->flash('file_size_error', 'Dosya boyutu.');
                        return back();
                    }

                    /** ►►►►► DEVELOPER ◄◄◄◄◄
                     * Delete old files if there is.
                     */
                    if (file_exists($user->profile_image)) {
                        unlink($user->profile_image);
                    }

                    /** ►►►►► DEVELOPER ◄◄◄◄◄
                     * Saving image's file path.
                     */
                    $user->profile_image = $result;
                    if ($user->save()) {
                        $request->session()->flash('success', 'Kullanıcı Güncellendi');
                    } else {
                        $request->session()->flash('error', 'Kullanıcı Güncellenemedi');
                    }
                }
                session()->flash('tab_page','profile_image');                 //Which tab will be opened.
                return back();

            /** ►►►►► DEVELOPER ◄◄◄◄◄
             * Form request change password.
             */
            }else if ($request->has('updateuserinfo')) {
                $request->validate([
                    'name' => 'string|max:100']);
                $user->name = $request->name;
                $user->title = $request->user_title;

                if(Auth::id() == $user->id){
                    $user->is_active = 1;
                }else{
                    if ('on' == $request->is_active) {
                        $user->is_active = 1;
                    } else {
                        $user->is_active = 0;
                    }
                }
                if ($user->save()) {
                    $request->session()->flash('success', 'Kullanıcı Güncellendi');
                } else {
                    $request->session()->flash('error', 'Kullanıcı Güncellenemedi');
                }
                session()->flash('tab_page','user_info');                 //Which tab will be opened.
                return back();

            }else if ($request->has('updateuserpwd')) {
                session()->flash('tab_page','password');                 //Which tab will be opened.
                if (Hash::check($request->password_old, $user->password)) {
                    if ($request->password == $request->confirm_password) {
                        $user->password = Hash::make($request->password);
                        if ($user->save()) {
                            $request->session()->flash('success', 'Şifre Değiştirildi');
                        } else {
                            $request->session()->flash('error', 'Şifre Değiştirilemedi');
                        }
                        return back();
                    } else {
                        $request->session()->flash('error_pwd', 'Şifreler eşleşmiyor');
                        return back();
                    }
                } else {
                    $request->session()->flash('old_pwd', 'Eski Şifre Yanlış');
                    return back();
                }

            } else {
            return redirect()->route('admin.index');
            }
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
        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * Lower authority user can not destroy higher authority user's data.
         */
        if (Gate::allows('crud-show', User::findOrFail($id))) {
            /** ►►►►► DEVELOPER ◄◄◄◄◄
             * User can not destroy it's own data.
             */
            if ($id == Auth::id()) {
                session()->flash('own', 'Kendini Silemezsin');
                return redirect()->route('admin.user-settings.index');
            }
            $user = User::findOrFail($id);
            $user->delete();
            session()->flash('success', 'Silindi');
        } else {
            session()->flash('role', 'Yüksek Yetki Silinemez');
        }
        return redirect()->route('admin.user-settings.index');
    }
}
