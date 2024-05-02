<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description"
    content="Batuhan Basgul">
  <meta name="keywords"
    content="Batuhan Basgul">
  <meta name="author" content="Batuhan Basgul">
  <link rel="icon" href="{{ asset('uploads/media/constant/icon.png') }}" type="image/x-icon">
  <link rel="shortcut icon" href="{{ asset('uploads/media/constant/icon.png') }}" type="image/x-icon">
  <title>@yield('title')</title>
  <!-- Google font-->
  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link
    href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&amp;display=swap"
    rel="stylesheet">
  <link
    href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&amp;display=swap"
    rel="stylesheet">
  <link
    href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,400;0,500;0,600;0,700;0,800;0,900;1,300;1,400;1,500;1,600;1,700;1,800;1,900&amp;display=swap"
    rel="stylesheet">
    <link
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"
      rel="stylesheet">

  <!-- Font Awesome-->
  <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
  <!-- ico-font-->
  <link rel="stylesheet" type="text/css" href="{{ asset('admin-asset/css/icofont.css') }}">
  <!-- Themify icon-->
  <link rel="stylesheet" type="text/css" href="{{ asset('admin-asset/css/themify.css') }}">
  <!-- Flag icon-->
  <link rel="stylesheet" type="text/css" href="{{ asset('admin-asset/css/flag-icon.css') }}">
  <!-- Feather icon-->
  <link rel="stylesheet" type="text/css" href="{{ asset('admin-asset/css/feather-icon.css') }}">

  <!-- Plugins css start-->
  <link rel="stylesheet" type="text/css" href="{{ asset('admin-asset/css/animate.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('admin-asset/js/cropperjs/cropper.min.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('admin-asset/css/date-picker.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('admin-asset/css/sortable.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('admin-asset/css/nestable.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('admin-asset/css/photoswipe.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('admin-asset/css/datatables.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('admin-asset/css/datatable-extension.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('admin-asset/css/select2.css') }}">
  <!-- Plugins css Ends-->

  <!-- Bootstrap css-->
  <link rel="stylesheet" type="text/css" href="{{ asset('admin-asset/css/bootstrap.css') }}">
  <!-- App css-->
  <link rel="stylesheet" type="text/css" href="{{ asset('admin-asset/css/style.css') }}">
  <link id="color" rel="stylesheet" href="{{ asset('admin-asset/css/color-1.css') }}" media="screen">
  <!-- Responsive css-->
  <link rel="stylesheet" type="text/css" href="{{ asset('admin-asset/css/responsive.css') }}">
  <!-- Custom Assets -->
  <link rel="stylesheet" href="{{ asset('admin-asset/custom.css') }}">
  <!-- END Stylesheets -->
  <script src="https://cdn.ckeditor.com/ckeditor5/29.2.0/classic/ckeditor.js"></script>
  <!--CKEditor Sizing-->
  <style>
    .ck-editor__editable {
       min-height: 128px;
       max-height: 512px;
    }
  </style>
  <!-- Sweet Alert 2 -->
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
</head>

<body>
    <!-- Locale Lang Code for JS -->
    <input type="hidden" id="localeLang" value="{{ config('app.locale') }}">
  <!-- Loader starts-->
  <div class="loader-wrapper">
    <div class="theme-loader">
      <div class="loader-p"></div>
    </div>
  </div>
  <!-- Loader ends-->
  <!-- page-wrapper Start       -->
  <div class="page-wrapper compact-wrapper" id="pageWrapper">
    <!-- Page Header Start-->
    <div class="page-main-header">
      <div class="main-header-right row m-0">
        <div class="main-header-left">
          <div class="logo-wrapper">
            <a href="{{ route('admin.index') }}">
              <img class="img-fluid disappear-500" style="border-radius: 20%; width:50px; height: 50px;" src="{{ asset('uploads/media/constant/icon.png') }}" alt=""></a>
              <span class="ms-2" style="font-weight: bold; font-size: large;">CMS</span>
            </a>
          </div>
          <div class="dark-logo-wrapper">
            <img class="img-fluid disappear-500" style="border-radius: 20%; width:50px; height: 50px;" src="{{ asset('uploads/media/constant/icon.png') }}" alt=""></a>
            <span class="ms-2" style="font-weight: bold; font-size: large;">CMS</span>
          </div>
          <div class="toggle-sidebar"><i class="status_toggle middle" data-feather="align-center"
              id="sidebar-toggle"></i></div>
        </div>
        <div class="left-menu-header col d-none">
          <ul>
            <li>
              <form class="form-inline search-form">
                <div class="search-bg"><i class="fa fa-search"></i>
                  <input class="form-control-plaintext" placeholder="Search here.....">
                </div>
              </form><span class="d-sm-none mobile-search search-bg"><i class="fa fa-search"></i></span>
            </li>
          </ul>
        </div>
        <div class="nav-right col pull-right right-menu p-0">
          <ul class="nav-menus">
            <li class="onhover-dropdown">
              @if (session('unread_mails'))
              <div class="notification-box"><i data-feather="bell"></i><span class="dot-animated"></span></div>
              @else
              <div><i data-feather="bell"></i></div>
              @endif
              <ul class="notification-dropdown onhover-show-div">
                @if (session('unread_mails'))
                <li>
                  <p class="f-w-700 mb-0">{{ count(session('unread_mails')) }}{{__('admin_main.new_notice')}}<span
                      class="pull-right badge badge-primary badge-pill">{{ count(session('unread_mails')) }}</span></p>
                </li>
                @php $i=0; @endphp
                @foreach (session('unread_mails') as $mail)
                  @if ($i<5)
                  <a href="{{ route('admin.mail-box.show', [$mail->id]) }} ">
                    <li class="noti-primary">
                      <div class="media"><span class="notification-bg bg-light-primary">
                        <div style="font-size:1.5rem">
                          <i class="far fa-envelope"></i>
                        </div>
                        </span>
                        <div class="media-body">
                          <p>{{ $mail->name }} </p><span>{{ $mail->created_at->diffForHumans() }}</span>
                        </div>
                      </div>
                    </li>
                  </a>
                  @endif
                @php $i++; @endphp
                @endforeach
                @endif
              </ul>
            </li>
            <li>
              <form action="{{ route('admin.settings.update',[Auth::id()]) }}" method="POST">
                @csrf
                @method("PUT")
                @if (session('theme_dark'))
                <input type="hidden" id="theme_dark" name="theme_dark" value="0">
                <button type="submit" id="updateuserpreferences" name="updateuserpreferences" class="fabutton-dark">
                  <i class="far fa-moon"></i>
                </button>
                @else
                <input type="hidden" id="theme_dark" name="theme_dark" value="1">
                <button type="submit" id="updateuserpreferences" name="updateuserpreferences" class="fabutton-light">
                  <i class="far fa-moon"></i>
                </button>
                @endif
              </form>
            </li>
            <li>
                @if (session('lang_code') == 'tr')
                <a style="font-weight: bold; font-size: large;" href="{{ route('admin.change-locale',['lang'=>'en']) }}">TR</a>
                @else
                <a style="font-weight: bold; font-size: large;" href="{{ route('admin.change-locale',['lang'=>'tr']) }}">EN</a>
                @endif
            </li>
            <li class="p-0">
              <a href="{{ route('slug','/') }}" target="_blank">
                <span class="fs-sm fw-medium">
                  <button class="btn btn-primary-light" type="button"><i class="fas fa-external-link-alt me-1"></i>{{__('admin_aboutus.website')}}</button>
                </span>
              </a>
            </li>
            <li class="p-0">
              <a class="d-flex align-items-center justify-content-between" href="{{ route('logout') }}"
              onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
                </form>
                <span class="fs-sm fw-medium">
                  <button class="btn btn-primary-light" type="button"><i data-feather="log-out"></i>{{__('admin_main.logout')}}</button>
                </span>
              </a>
            </li>
          </ul>
        </div>
        <div class="d-lg-none mobile-toggle pull-right w-auto"><i data-feather="more-horizontal"></i></div>
      </div>
    </div>
    <!-- Page Header Ends                              -->
    <!-- Page Body Start-->
    <div class="page-body-wrapper sidebar-icon">
      <!-- Page Sidebar Start-->
      <header class="main-nav pt-3 mb-3">
        <nav>
          <div class="main-navbar">
            <div id="mainnav">
              <ul class="nav-menu custom-scrollbar">
                <li class="back-btn">
                  <div class="mobile-back text-end"><span>Back</span><i class="fa fa-angle-right ps-2"
                      aria-hidden="true"></i></div>
                </li>
                <li class="sidebar-main-title">
                  <div>
                    <h6>{{__('admin_main.general')}} </h6>
                  </div>
                </li>
                <li><a class="nav-link menu-title link-nav @if (session('selectedSideMenu') == 'admin') active @endif"
                   href="{{ route('admin.index') }}"><i class="me-3 fa-solid fa-house-chimney"></i><span>{{__('admin_main.main_page')}}</span>
                  @can('master', Auth::id())
                    @if (session('construction'))
                    <i class="fa fa-fw fa-exclamation-circle text-danger"></i>
                    @endif
                  @endcan
                  @if (session('maintenance'))
                    <i class="fa fa-fw fa-exclamation-circle text-danger"></i>
                  @endif
                </a></li>
                <li><a class="nav-link menu-title link-nav @if (session('selectedSideMenu') == 'mail-box') active @endif"
                   href="{{ route('admin.mail-box.index') }}"><i class="me-3 fa-solid fa-envelopes-bulk"></i><span>{{__('admin_main.messages')}}</span>
                  @if (session('unread_mails'))
                    <span class="badge rounded-pill bg-danger" style="font-size: 0.55rem">
                        {{ count(session('unread_mails')) }}
                    </span>
                  @endif
                </a></li>
                <li><a class="nav-link menu-title link-nav @if (session('selectedSideMenu') == 'user-settings') active @endif" href="{{ route('admin.user-settings.index') }}"><i class="me-3 fa-solid fa-users"></i><span>{{__('admin_main.users')}}</span></a></li>
                <li><a class="nav-link menu-title
                    @if (session('selectedSideMenu') == 'app-settings' ||
                          session('selectedSideMenu') == 'lang-settings' ||
                          session('selectedSideMenu') == 'maintenance' ||
                          session('selectedSideMenu') == 'app-maintenance')
                      active" href="javascript:void(0)"><i class="me-3 fa-solid fa-gears"></i><span>{{__('admin_main.app_settings')}}</span></a>
                      <ul class="nav-submenu menu-content" style="display: block;">
                    @else
                      " href="javascript:void(0)"><i class="me-3 fa-solid fa-gears"></i><span>{{__('admin_main.app_settings')}}</span></a>
                      <ul class="nav-submenu menu-content">
                    @endif
                    <li><a @if (session('selectedSideMenu') == 'app-settings') class="active selected-menu-item" @endif
                      href="{{ route('admin.app-settings.index',['lang_code' => App::getLocale()]) }}"><i class="me-3 fa-solid fa-gear"></i><span>{{__('admin_main.settings')}}</span></a></li>
                    <li><a @if (session('selectedSideMenu') == 'lang-settings') class="active selected-menu-item" @endif
                      href="{{ route('admin.lang-settings.index')}}"><i class="me-3 fa-solid fa-language"></i><span>{{__('admin_main.lang_settings')}}</span></a></li>
                    <li><a @if (session('selectedSideMenu') == 'maintenance') class="active selected-menu-item" @endif
                      href="{{ route('admin.settings.edit',['user_id' => Auth::id(), 'setting' => 'maintenance']) }}"><i class="me-3 fa-solid fa-screwdriver-wrench"></i><span>{{__('admin_main.maintenance_settings')}}</span></a></li>
                    <li><a class="@if (session('selectedSideMenu') == 'app-maintenance') active selected-menu-item @endif"
                      href="{{ route('admin.app-maintenance.index', ['lang_code' => App::getLocale()]) }}"><i class="me-3 fa-solid fa-trowel-bricks"></i><span>{{__('admin_main.app_maintenance_page')}}</span></a></li>
                  </ul>
                </li>
                <li class="sidebar-main-title">
                  <div>
                    <h6>{{__('admin_main.app_content')}} </h6>
                  </div>
                </li>
                <li class="dropdown"><a class="nav-link menu-title
                  @if (session('selectedSideMenu') == 'header-settings' ||
                        session('selectedSideMenu') == 'menu-settings')
                    active" href="javascript:void(0)"><i class="me-3 fa-brands fa-accusoft"></i><span>{{__('admin_main.header_settings')}}</span></a>
                    <ul class="nav-submenu menu-content" style="display: block;">
                  @else
                    " href="javascript:void(0)"><i class="me-3 fa-brands fa-accusoft"></i><span>{{__('admin_main.header_settings')}}</span></a>
                    <ul class="nav-submenu menu-content">
                  @endif
                    <li><a @if (session('selectedSideMenu') == 'header-settings') class="active selected-menu-item" @endif
                      href="{{ route('admin.header-settings.index',['lang_code' => App::getLocale()]) }}"><i class="me-3 fa-solid fa-book"></i><span>{{__('admin_main.header_content')}}</span></a></li>
                    <li><a @if (session('selectedSideMenu') == 'menu-settings') class="active selected-menu-item" @endif
                      href="{{ route('admin.menu-settings.index', ['show'=>'active', 'lang_code' => App::getLocale()]) }}"><i class="me-3 fa-solid fa-list"></i></i><span>{{__('admin_main.menu')}}</span></a></li>
                  </ul>
                </li>
                <li class="dropdown"><a class="nav-link menu-title
                  @if (session('selectedSideMenu') == 'popup-settings' ||
                        session('selectedSideMenu') == 'slider-settings' ||
                        session('selectedSideMenu') == 'app-card-settings' ||
                        session('selectedSideMenu') == 'app-reference-settings' ||
                        session('selectedSideMenu') == 'app-blog-settings')
                    active" href="javascript:void(0)"><i class="me-3 fa-solid fa-house"></i><span>{{__('admin_main.mainpage_settings')}}</span></a>
                    <ul class="nav-submenu menu-content" style="display: block;">
                  @else
                    " href="javascript:void(0)"><i class="me-3 fa-solid fa-house"></i><span>{{__('admin_main.mainpage_settings')}}</span></a>
                    <ul class="nav-submenu menu-content">
                  @endif
                    <li><a @if (session('selectedSideMenu') == 'popup-settings') class="active selected-menu-item" @endif
                      href="{{ route('admin.popup-settings.index',['lang_code' => App::getLocale()]) }}"><i class="me-3 fa-solid fa-comment-dots"></i><span>{{__('admin_main.popup')}}</span></a></li>
                    <li><a @if (session('selectedSideMenu') == 'slider-settings') class="active selected-menu-item" @endif
                      href="{{ route('admin.slider-settings.index',['lang_code' => App::getLocale()]) }}"><i class="me-3 fa-solid fa-photo-film"></i></i><span>{{__('admin_main.slider')}}</span></a></li>
                    <li><a @if (session('selectedSideMenu') == 'app-blog-settings') class="active selected-menu-item" @endif
                      href="{{ route('admin.app-blog-settings.index', ['lang_code' => App::getLocale()])}}"><i class="me-3 fa-solid fa-bullhorn"></i><span>{{__('admin_main.announcements')}}</span></a></li>
                    <li><a @if (session('selectedSideMenu') == 'app-card-settings') class="active selected-menu-item" @endif
                      href="{{ route('admin.app-card-settings.index', ['lang_code' => App::getLocale()])}}"><i class="me-3 fa-solid fa-clone"></i><span>{{__('admin_main.cards')}}</span></a></li>
                    <li><a @if (session('selectedSideMenu') == 'app-reference-settings') class="active selected-menu-item" @endif
                      href="{{ route('admin.app-reference-settings.index', ['lang_code' => App::getLocale()])}}"><i class="me-3 fa-solid fa-handshake"></i><span>{{__('admin_main.references')}}</span></a></li>
                  </ul>
                </li>
                <li class="dropdown"><a class="nav-link menu-title
                  @if (session('selectedSideMenu') == 'about-us-settings' ||
                        session('selectedSideMenu') == 'about-us-cards')
                    active" href="javascript:void(0)"><i class="me-3 fa-solid fa-house"></i><span>{{__('admin_main.about_us')}}</span></a>
                    <ul class="nav-submenu menu-content" style="display: block;">
                  @else
                    " href="javascript:void(0)"><i class="me-3 fa-solid fa-house"></i><span>{{__('admin_main.about_us')}}</span></a>
                    <ul class="nav-submenu menu-content">
                  @endif
                    <li><a @if (session('selectedSideMenu') == 'about-us-settings') class="active selected-menu-item" @endif
                      href="{{ route('admin.about-us-settings.index',['lang_code' => App::getLocale()]) }}"><i class="me-3 fa-solid fa-comment-dots"></i><span>{{__('admin_main.about_us')}}</span></a></li>
                    <li><a @if (session('selectedSideMenu') == 'about-us-cards') class="active selected-menu-item" @endif
                      href="{{ route('admin.about-us-cards-settings.index',['lang_code' => App::getLocale()]) }}"><i class="me-3 fa-solid fa-photo-film"></i></i><span>{{__('admin_main.about_us_cards')}}</span></a></li>
                  </ul>
                </li>
                <li><a class="nav-link menu-title link-nav @if (session('selectedSideMenu') == 'gallery-info-settings') active @endif"
                  href="{{ route('admin.gallery-info-settings.index', ['lang_code' => App::getLocale()]) }}"><i class="me-3 fa-solid fa-image"></i><span>{{__('admin_main.gallery')}}</span></a></li>
                <!--
                <li class="dropdown"><a class="nav-link menu-title
                  @if (session('selectedSideMenu') == 'announcement-info-settings' ||
                        session('selectedSideMenu') == 'announcement-settings')
                    active" href="javascript:void(0)"><i class="me-3 fa-solid fa-bullhorn"></i><span>{{__('admin_main.announcements')}}</span></a>
                    <ul class="nav-submenu menu-content" style="display: block;">
                  @else
                    " href="javascript:void(0)"><i class="me-3 fa-solid fa-bullhorn"></i><span>{{__('admin_main.announcements')}}</span></a>
                    <ul class="nav-submenu menu-content">
                  @endif
                    <li><a @if (session('selectedSideMenu') == 'announcement-info-settings') class="active selected-menu-item" @endif
                      href="{{ route('admin.announcement-info-settings.index',['lang_code' => App::getLocale()]) }}"><i class="me-3 fa-solid fa-scroll"></i><span>{{__('admin_main.announcements_page')}}</span></a></li>
                    <li><a @if (session('selectedSideMenu') == 'announcement-settings') class="active selected-menu-item" @endif
                      href="{{ route('admin.announcement-settings.index',['lang_code' => App::getLocale()]) }}"><i class="me-3 fa-solid fa-scroll"></i><span>{{__('admin_main.announcements')}}</span></a></li>
                  </ul>
                </li>
                -->
                <li class="dropdown"><a class="nav-link menu-title
                  @if (session('selectedSideMenu') == 'reference-info-settings' ||
                        session('selectedSideMenu') == 'reference-settings')
                    active" href="javascript:void(0)"><i class="me-3 fa-solid fa-handshake"></i><span>{{__('admin_main.references')}}</span></a>
                    <ul class="nav-submenu menu-content" style="display: block;">
                  @else
                    " href="javascript:void(0)"><i class="me-3 fa-solid fa-handshake"></i><span>{{__('admin_main.references')}}</span></a>
                    <ul class="nav-submenu menu-content">
                  @endif
                  <!--
                    <li><a @if (session('selectedSideMenu') == 'reference-info-settings') class="active selected-menu-item" @endif
                      href="{{ route('admin.reference-info-settings.index',['lang_code' => App::getLocale()]) }}"><i class="me-3 fa-solid fa-book"></i><span>{{__('admin_main.references_page')}}</span></a></li>
                    -->
                      <li><a @if (session('selectedSideMenu') == 'reference-settings') class="active selected-menu-item" @endif
                      href="{{ route('admin.reference-settings.index',['lang_code' => App::getLocale()]) }}"><i class="me-3 fa-solid fa-clone"></i><span>{{__('admin_main.references')}}</span></a></li>
                  </ul>
                </li>
                <li class="dropdown"><a class="nav-link menu-title
                  @if (session('selectedSideMenu') == 'pricing-info-settings' ||
                        session('selectedSideMenu') == 'pricing-settings')
                    active" href="javascript:void(0)"><i class="me-3 fa-solid fa-money-bill"></i></i><span>{{__('admin_main.prices')}}</span></a>
                    <ul class="nav-submenu menu-content" style="display: block;">
                  @else
                    " href="javascript:void(0)"><i class="me-3 fa-solid fa-money-bill"></i><span>{{__('admin_main.prices')}}</span></a>
                    <ul class="nav-submenu menu-content">
                  @endif
                    <li><a @if (session('selectedSideMenu') == 'pricing-info-settings') class="active selected-menu-item" @endif
                      href="{{ route('admin.pricing-info-settings.index',['lang_code' => App::getLocale()]) }}"><i class="me-3 fa-solid fa-book"></i><span>{{__('admin_main.prices_page')}}</span></a></li>
                    <li><a @if (session('selectedSideMenu') == 'pricing-settings') class="active selected-menu-item" @endif
                      href="{{ route('admin.pricing-settings.index',['lang_code' => App::getLocale()]) }}"><i class="me-3 fa-solid fa-clone"></i><span>{{__('admin_main.prices')}}</span></a></li>
                  </ul>
                </li>
                <li><a class="nav-link menu-title link-nav @if (session('selectedSideMenu') == 'page-settings') active @endif"
                  href="{{ route('admin.page-settings.index',['lang_code' => App::getLocale()]) }}"><i class="me-3 fa-solid fa-copy"></i><span>{{__('admin_main.custom_pages')}}</span></a></li>
                <li class="dropdown"><a class="nav-link menu-title
                  @if (session('selectedSideMenu') == 'products-info-settings' ||
                        session('selectedSideMenu') == 'products-category-settings' ||
                        session('selectedSideMenu') == 'products-settings')
                    active" href="javascript:void(0)"><i class="me-3 fa-solid fa-leaf"></i><span>{{__('admin_main.products')}}</span></a>
                    <ul class="nav-submenu menu-content" style="display: block;">
                  @else
                    " href="javascript:void(0)"><i class="me-3 fa-solid fa-leaf"></i><span>{{__('admin_main.products')}}</span></a>
                    <ul class="nav-submenu menu-content">
                  @endif
                    <li><a @if (session('selectedSideMenu') == 'products-info-settings') class="active selected-menu-item" @endif
                      href="{{ route('admin.products-info-settings.index',['lang_code'=>App::getLocale()]) }}"><i class="me-3 fa-solid fa-book"></i><span>{{__('admin_main.products_page')}}</span></a></li>
                    <li><a @if (session('selectedSideMenu') == 'products-category-settings') class="active selected-menu-item" @endif
                      href="{{ route('admin.products-category-settings.index',['lang_code'=>App::getLocale()]) }}"><i class="me-3 fa-solid fa-clone"></i><span>{{__('admin_main.products_categories')}}</span></a></li>
                    <li><a @if (session('selectedSideMenu') == 'products-settings') class="active selected-menu-item" @endif
                      href="{{ route('admin.products-settings.index',['lang_code'=>App::getLocale()]) }}"><i class="me-3 fa-solid fa-apple-whole"></i><span>{{__('admin_main.products')}}</span></a></li>
                  </ul>
                </li>
                <li><a class="nav-link menu-title link-nav @if (session('selectedSideMenu') == 'contact-settings') active @endif"
                  href="{{ route('admin.contact-settings.index',['lang_code' => App::getLocale()]) }}"><i class="me-3 fa-solid fa-phone"></i><span>{{__('admin_main.contact')}}</span></a></li>
                <li><a class="nav-link menu-title link-nav @if (session('selectedSideMenu') == 'constant-settings') active @endif"
                  href="{{ route('admin.constant-settings.index',['lang_code' => App::getLocale()]) }}"><i class="me-3 fa-solid fa-hammer"></i><span>{{__('admin_main.constant_fields')}}</span></a></li>
                <li><a class="nav-link menu-title link-nav @if (session('selectedSideMenu') == 'footer-settings') active @endif"
                  href="{{ route('admin.footer-settings.index',['lang_code' => App::getLocale()]) }}"><i class="me-3 fa-solid fa-tent-arrow-down-to-line"></i><span>{{__('admin_main.footer')}}</span></a></li>

                @can('master')
                <li class="sidebar-main-title">
                  <div>
                    <h6>{{__('admin_main.master')}} </h6>
                  </div>
                </li>
                <li class="dropdown" style="margin-bottom:25%"><a class="nav-link menu-title
                  @if (session('selectedSideMenu') == 'image-manager' ||
                        session('selectedSideMenu') == 'construction' ||
                        session('selectedSideMenu') == 'panel-maintenance'
                        )
                    active" href="javascript:void(0)"><i class="me-3 fa-solid fa-book"></i><span>{{__('admin_main.contents')}}</span></a>
                    <ul class="nav-submenu menu-content" style="display: block;">
                  @else
                    " href="javascript:void(0)"><i class="me-3 fa-solid fa-book"></i><span>{{__('admin_main.contents')}}</span></a>
                    <ul class="nav-submenu menu-content">
                  @endif
                    <li><a class="@if (session('selectedSideMenu') == 'image-manager') active selected-menu-item @endif"
                      href="{{ route('admin.image-manager.index', ['lang_code' => App::getLocale()]) }}"><i class="me-3 fa-solid fa-images"></i><span>{{__('admin_main.image_manager')}}</span></a></li>
                    <li><a class="@if (session('selectedSideMenu') == 'construction') active selected-menu-item @endif"
                      href="{{ route('admin.construction.index', ['lang_code' => App::getLocale()]) }}"><i class="me-3 fa-solid fa-trowel-bricks"></i><span>{{__('admin_main.construction_page')}}</span></a></li>
                      <li><a class="@if (session('selectedSideMenu') == 'panel-maintenance') active selected-menu-item @endif"
                        href="{{ route('admin.panel-maintenance.index', ['lang_code' => App::getLocale()]) }}"><i class="me-3 fa-solid fa-trowel-bricks"></i><span>{{__('admin_main.panel_maintenance_page')}}</span></a></li>
                  </ul>
                </li>
                @endcan
              </ul>
            </div>
          </div>
        </nav>
      </header>
      <!-- Page Sidebar Ends-->
