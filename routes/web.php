<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use App\Models\Construction;
use App\Models\AppMaintenanceModel;
use App\Models\PanelMaintenanceModel;
use App\Models\User;
use App\Models\Setting;
use App\Http\Controllers\AboutUsSettingsController;
use App\Http\Controllers\AboutUsCardsController;
use App\Http\Controllers\AdminPanelController;
use App\Http\Controllers\AnnouncementImageController;
use App\Http\Controllers\AnnouncementInfoSettingsController;
use App\Http\Controllers\AnnouncementSettingsController;
use App\Http\Controllers\AppSettingsController;
use App\Http\Controllers\ContactSettingsController;
use App\Http\Controllers\ContactUsController;
use App\Http\Controllers\FooterSettingsController;
use App\Http\Controllers\AppBlogController;
use App\Http\Controllers\AppReferenceController;
use App\Http\Controllers\GalleryInfoSettingsController;
use App\Http\Controllers\GallerySettingsController;
use App\Http\Controllers\HeaderSettingsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LangController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\ProductImageController;
use App\Http\Controllers\ProductsCategorySettingsController;
use App\Http\Controllers\ProductsInfoSettingsController;
use App\Http\Controllers\ProductsSettingsController;
use App\Http\Controllers\ReferenceInfoSettingsController;
use App\Http\Controllers\ReferenceSettingsController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SliderSettingsController;
use App\Http\Controllers\UserSettingsController;
use App\Http\Controllers\ImageManagerController;
use App\Http\Controllers\ConstructionController;
use App\Http\Controllers\AppMaintenanceController;
use App\Http\Controllers\PanelMaintenanceController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PageImageController;
use App\Http\Controllers\AppCardController;
use App\Http\Controllers\ConstantSettings;
use App\Http\Controllers\PopupController;
use App\Http\Controllers\PricingInfoSettingsController;
use App\Http\Controllers\PricingSettingsController;
use App\Http\Middleware\AppMaintenance;
use App\Http\Middleware\PanelMaintenance;
use App\Http\Middleware\UserMiddleware;
use App\Http\Middleware\ConstructionMiddleware;

Auth::routes();

/**
 * MAINTENANCE PAGES
 */
Route::get('/status/maintenance-panel', function () {
    if(PanelMaintenanceModel::where('lang_code',Lang::getLocale())->first()){
        $panelMaintenance = PanelMaintenanceModel::where('lang_code',Lang::getLocale())->first();
    }else{
        $panelMaintenance = PanelMaintenanceModel::first();
    }
    if(!Setting::first()->maintenance_panel){
        return redirect()->route('admin.index');
    }else{
        return view('maintenance-panel', ['content' => $panelMaintenance]);
    }
})->name('maintenance-panel');
Route::get('/status/maintenance-app', function () {
    if(AppMaintenanceModel::where('lang_code',Lang::getLocale())->first()){
        $appMaintenance = AppMaintenanceModel::where('lang_code',Lang::getLocale())->first();
    }else{
        $appMaintenance = AppMaintenanceModel::first();
    }
    if(!Setting::first()->maintenance_app){
        return redirect()->route('index');
    }else{
        return view('maintenance-app', ['content' => $appMaintenance]);
    }
})->name('maintenance-app');


/**
 * PASSIVE USER SCREEN
 */
Route::get('/status/user-passive', function () {
    if(Auth::id()){
        if(!User::findOrFail(Auth::id())->is_active){
            return view('user-passive');
        }else{
            return redirect()->route('admin.index');
        }
    }else{
        return redirect()->route('admin.index');
    }
})->name('user-passive');

/**
 * CONSTRUCTION PAGE
 */
Route::get('/status/construction', function () {
    if(Construction::where('lang_code',Lang::getLocale())->first()){
        $construction = Construction::where('lang_code',Lang::getLocale())->first();
    }else{
        $construction = Construction::first();
    }
    if(!$construction->is_active){
        return redirect()->route('index');
    }else{
        return view('construction', ['content' => $construction]);
    }
})->name('construction');

/*
ADMIN PANEL
 */
Route::middleware([UserMiddleware::class])->group(function() {
    Route::middleware([PanelMaintenance::class])->group(function () {
        Route::prefix('/admin')->name('admin.')->group(function () {
            /**
             * LANG CHANGE
             */
            Route::get('/change-locale/{lang}', [AdminPanelController::class, 'changeLocale'])->name('change-locale');

            /**
             * ABOUT US SETTINGS
             */
            Route::resource('about-us-settings', AboutUsSettingsController::class)
                ->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);

            /**
             * ABOUT US CARDS
             */
            Route::resource('about-us-cards-settings', AboutUsCardsController::class)
                ->only(['index', 'create', 'store', 'edit', 'update']);
            //Out of resource routes.
            Route::get('about-us-cards-settings/edit-order', [AboutUsCardsController::class, 'editOrder'])->name('about-us-cards-settings.edit-order');
            //Needs another form on resource, this way does not.
            Route::get('about-us-cards-settings/destroy/{id}', [AboutUsCardsController::class, 'destroy'])->name('about-us-cards-settings.destroy');

            /**
             * ANNOUNCEMENT PAGE SETTINGS
             */
            Route::resource('announcement-info-settings', AnnouncementInfoSettingsController::class)
                ->only(['index', 'create', 'store', 'edit', 'update']);

            /**
             * ANNOUNCEMENTS
             */
            Route::resource('announcement-settings', AnnouncementSettingsController::class)
                ->only(['index', 'create', 'store', 'edit', 'update']);
            //Out of resource routes.
            Route::get('announcement-settings/edit-order', [AnnouncementSettingsController::class, 'editOrder'])->name('announcement-settings.edit-order');
            //Needs another form on resource, this way does not.
            Route::get('announcement-settings/destroy/{id}', [AnnouncementSettingsController::class, 'destroy'])->name('announcement-settings.destroy');

            /**
             * ANNOUNCEMENT IMAGES - MORE DETAILED RATHER THAN GENERAL GALLERY FOR PAGES
             */
            Route::resource('announcement-images', AnnouncementImageController::class)
                ->only(['edit', 'update']);
            //Out of resource routes.
            Route::get('announcement-images-settings/edit-order', [AnnouncementImageController::class, 'editOrder'])->name('announcement-images-settings.edit-order');
            //Needs another form on resource, this way does not.
            Route::get('announcement-images/destroy/{id}', [AnnouncementImageController::class, 'destroy'])->name('announcement-images.destroy');

            /**
             * APP BLOG PAGE DATA - PANEL HOMAPAGE SECTION
             */
            Route::resource('app-blog-settings', AppBlogController::class)
                ->only(['index', 'create', 'store', 'edit', 'update']);

            /**
             * APP CARDS PAGE DATA - PANEL HOMAPAGE SECTION
             */
            Route::resource('app-card-settings', AppCardController::class)
            ->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
            //Out of resource routes.
            Route::get('app-card-settings/edit-order', [AppCardController::class, 'editOrder'])->name('app-card-settings.edit-order');

            /**
             * APP REFERENCE PAGE - PANEL HOMAPAGE SECTION
             */
            Route::resource('app-reference-settings', AppReferenceController::class)
                ->only(['index', 'create', 'store', 'edit', 'update']);

            /**
             * APP SETTINGS - APP GENERAL DATA
             */
            Route::resource('app-settings', AppSettingsController::class)
                ->only(['index', 'store', 'update']);

            /**
             * APP CONSTANT SETTINGS
             */
            Route::resource('constant-settings', ConstantSettings::class)
                ->only(['index', 'create', 'store', 'edit', 'update']);

            /**
             * APP CONSTRUCTION PAGE SETTINGS
             */
            Route::resource('construction', ConstructionController::class)
            ->only(['index', 'store', 'update']);

            /**
             * APP MAINTENANCE PAGE SETTINGS
             */
            Route::resource('app-maintenance', AppMaintenanceController::class)
            ->only(['index', 'store', 'update']);

            /**
             * PANEL MAINTENANCE PAGE SETTINGS
             */
            Route::resource('panel-maintenance', PanelMaintenanceController::class)
            ->only(['index', 'store', 'update']);

            /**
             * CONTACT PAGE SETTINGS
             */
            Route::resource('contact-settings', ContactSettingsController::class)
                ->only(['index', 'create', 'store', 'edit', 'update']);

            /**
             * FOOTER SETTINGS
             */
            Route::resource('footer-settings', FooterSettingsController::class)
            ->only(['index', 'create', 'store', 'edit', 'update']);

            /**
             * GALLERY PAGE
             */
            Route::resource('gallery-info-settings', GalleryInfoSettingsController::class)
                ->only(['index', 'create', 'store', 'edit', 'update']);

            /**
             * PAGE GALLERIES
             */
            Route::resource('gallery-settings', GallerySettingsController::class)
                ->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
            //Out of resource routes.
            Route::get('gallery-settings/edit-order', [GallerySettingsController::class, 'editOrder'])->name('gallery-settings.edit-order');

            /**
             * HEADER SETTINGS
             */
            Route::resource('header-settings', HeaderSettingsController::class)
                ->only(['index', 'update']);

            /**
             * IMAGE MANAGER SETTINGS - SIZE&QUALITY SETTINGS
             */
            Route::resource('image-manager', ImageManagerController::class)
                ->only(['index', 'update']);

            /**
             * INDEX PAGE
             */
            Route::resource('/', AdminPanelController::class)
                ->only(['index']);

            /**
             * LANGUAGE SETTINGS
             */
            Route::resource('lang-settings', LangController::class)
                ->only(['index', 'create', 'store', 'edit', 'update']);
            //Out of resource routes.
            Route::get('lang-settings/edit-order', [LangController::class, 'editOrder'])->name('lang-settings.edit-order');

            /**
             * MAIL CONTROLLER
             */
            Route::resource('mail-box', MailController::class)
                ->only(['index', 'show', 'destroy']);

            /**
             * MENU SETTINGS
             */
            Route::resource('menu-settings', MenuController::class)
                ->only(['index', 'create', 'store', 'edit', 'update']);
                //Out of resource routes.
            Route::get('menu-settings/edit-order', [MenuController::class, 'editOrder'])->name('menu-settings.edit-order');

            /**
             * CUSTOM PAGE SETTINGS
             */
            Route::resource('page-settings', PageController::class)
                ->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);

            /**
             * CUSTOM PAGE IMAGES SETTINGS
             */
            Route::resource('page-images', PageImageController::class)
                ->only(['edit', 'update']);
            //Out of resource routes.
            Route::get('page-images-settings/edit-order', [PageImageController::class, 'editOrder'])->name('page-images-settings.edit-order');
            //Needs another form on resource, this way does not.
            Route::get('page-images/destroy/{id}', [PageImageController::class, 'destroy'])->name('page-images.destroy');

            /**
             * POP_UP SETTINGS
             */
            Route::resource('popup-settings', PopupController::class)
                ->only(['index', 'create', 'store', 'edit', 'update']);

            /**
             * PRODUCTS PAGE SETTINGS
             */
            Route::resource('products-info-settings', ProductsInfoSettingsController::class)
                ->only(['index', 'create', 'store', 'update']);

            /**
             * PRODUCTS SETTINGS
             */
            Route::resource('products-settings', ProductsSettingsController::class)
                ->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
            //Out of resource routes.
            Route::get('products-settings/edit-order', [ProductsSettingsController::class, 'editOrder'])->name('products-settings.edit-order');
            //Needs another form on resource, this way does not.
            Route::get('products-settings/destroy/{id}', [ProductsSettingsController::class, 'destroy'])->name('products-settings.destroy');

            /**
             * PRODUCT IMAGES - MORE DETAILED RATHER THAN GENERAL GALLERY FOR PAGES
             */
            Route::resource('product-images', ProductImageController::class)
                ->only(['edit', 'update',]);
            //Out of resource routes.
            Route::get('product-images-settings/edit-order', [ProductImageController::class, 'editOrder'])->name('product-images-settings.edit-order');
            //Needs another form on resource, this way does not.
            Route::get('product-images-settings/destroy/{id}', [ProductImageController::class, 'destroy'])->name('product-images-settings.destroy');

            /**
             * PRODUCT CATEGORY SETTINGS
             */
            Route::resource('products-category-settings', ProductsCategorySettingsController::class)
                ->only(['index', 'create', 'store', 'edit', 'update']);
            //Out of resource routes.
            Route::get('products-category-settings/edit-order', [ProductsCategorySettingsController::class, 'editOrder'])->name('products-category-settings.edit-order');
            //Needs another form on resource, this way does not.
            Route::get('products-category-settings/destroy/{id}', [ProductsCategorySettingsController::class, 'destroy'])->name('products-category-settings.destroy');

            /**
             * REFERENCES PAGE SETTINGS
             */
            Route::resource('reference-info-settings', ReferenceInfoSettingsController::class)
                ->only(['index', 'create', 'store', 'edit', 'update']);

            /**
             * REFERENCES SETTINGS
             */
            Route::resource('reference-settings', ReferenceSettingsController::class)
                ->only(['index', 'create', 'store', 'edit', 'update']);
            //Out of resource routes.
            Route::get('reference-settings/edit-order', [ReferenceSettingsController::class, 'editOrder'])->name('reference-settings.edit-order');
            //Needs another form on resource, this way does not.
            Route::get('reference-settings/destroy/{id}', [ReferenceSettingsController::class, 'destroy'])->name('reference-settings.destroy');

            /**
             * PRICING PAGE SETTINGS
             */
            Route::resource('pricing-info-settings', PricingInfoSettingsController::class)
                ->only(['index', 'create', 'store', 'edit', 'update']);

            /**
             * PRICING SETTINGS
             */
            Route::resource('pricing-settings', PricingSettingsController::class)
                ->only(['index', 'create', 'store', 'edit', 'update']);
            //Out of resource routes.
            Route::get('pricing-settings/edit-order', [PricingSettingsController::class, 'editOrder'])->name('pricing-settings.edit-order');
            //Needs another form on resource, this way does not.
            Route::get('pricing-settings/destroy/{id}', [PricingSettingsController::class, 'destroy'])->name('pricing-settings.destroy');

            /**
             * SETTINGS
             */
            Route::resource('settings', SettingController::class)
                ->only(['index', 'edit', 'update']);

            /**
             * SLIDER SETTINGS
             */
            Route::resource('slider-settings', SliderSettingsController::class)
                ->only(['index', 'create', 'store', 'edit', 'update']);
            //Out of resource routes.
            Route::get('slider-settings/edit-order', [SliderSettingsController::class, 'editOrder'])->name('slider-settings.edit-order');
            //Needs another form on resource, this way does not.
            Route::get('slider-settings/destroy/{id}', [SliderSettingsController::class, 'destroy'])->name('slider-settings.destroy');

            /**
             * USER SETTINGS
             */
            Route::resource('user-settings', UserSettingsController::class)
                ->only(['index', 'create', 'store', 'edit', 'update']);
            //Needs another form on resource, this way does not.
            Route::get('user-settings/destroy/{id}', [UserSettingsController::class, 'destroy'])->name('user-settings.destroy');

        });
    });
});

/*
HOME_PAGE
 */
Route::middleware([ConstructionMiddleware::class])->group(function (){
    Route::middleware([AppMaintenance::class])->group(function () {

        Route::get('/', [HomeController::class, 'index'])->name('index');

        Route::get('/contact-page', function () {
            return view('contact-page');
        });

        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * Change Language
         */
        Route::get('/change-locale/{lang}', [HomeController::class, 'changeLocale'])->name('change-locale');

        /** ►►►►► DEVELOPER ◄◄◄◄◄
         *  ContactUsController for guest users
         *  MailController for authenticated users
         */
        Route::resource('contact-us', ContactUsController::class)
        ->only(['store']);

        /** ►►►►► DEVELOPER ◄◄◄◄◄
         * Write slug to end or it blocks other routes on Home Page.
         */

        Route::get('/{slug}', [HomeController::class, 'slug'])->name('slug');
        Route::get('/{slug}/{subSlug}', [HomeController::class, 'slug', 'subSlug'])->name('doubleSlug');
        Route::get('/{slug}/{subSlug}/{subSubSlug}', [HomeController::class, 'slug', 'subSlug', 'subSubSlug'])->name('tripleSlug');
        Route::get('/{slug}/{subSlug}/{subSubSlug}/{subSubSubSlug}', [HomeController::class, 'slug', 'subSlug', 'subSubSlug', 'subSubSubSlug'])->name('fourSlug');
        Route::get('/{slug}/{subSlug}/{subSubSlug}/{subSubSubSlug}/{lastSlug}', [HomeController::class, 'slug', 'subSlug', 'subSubSlug', 'subSubSubSlug','lastSlug'])->name('fiveSlug');

    });
});
