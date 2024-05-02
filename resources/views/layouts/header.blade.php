@php
  date_default_timezone_set('Europe/Istanbul');
@endphp
<!DOCTYPE html>
<html lang="en">
<head>

	<!-- Basic Page Needs
	================================================== -->
	<meta charset="utf-8">
    <title>@yield('title')</title>
    <meta name="description" content="{{ $appSetting->description }}">
    <meta name="keywords" content="@yield('keywords')">
	<meta name="author" content="">

	<!-- Mobile Specific Metas
	================================================== -->
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

	<!-- Favicons
	================================================== -->
	<link rel="icon" href="{{ asset($appSetting->fav_icon) }}" type="image/x-icon" />
	<link rel="apple-touch-icon-precomposed" sizes="144x144" href="{{ asset($appSetting->fav_icon) }}">
	<link rel="apple-touch-icon-precomposed" sizes="72x72" href="{{ asset($appSetting->fav_icon) }}">
	<link rel="apple-touch-icon-precomposed" href="{{ asset($appSetting->fav_icon) }}">

	<!-- CSS
	================================================== -->

	<!-- Bootstrap -->
	<link rel="stylesheet" href="{{ asset('app-asset/css/bootstrap.min.css') }}">
	<!-- Template styles-->
	<link rel="stylesheet" href="{{ asset('app-asset/css/style.css') }}">
	<!-- Responsive styles-->
	<link rel="stylesheet" href="{{ asset('app-asset/css/responsive.css') }}">
	<!-- FontAwesome -->
	<script src="https://kit.fontawesome.com/1f950e5862.js" crossorigin="anonymous"></script>
	<!-- Animation -->
	<link rel="stylesheet" href="{{ asset('app-asset/css/animate.css') }}">
	<!-- Prettyphoto -->
	<link rel="stylesheet" href="{{ asset('app-asset/css/prettyPhoto.css') }}">
	<!-- Owl Carousel -->
	<link rel="stylesheet" href="{{ asset('app-asset/css/owl.carousel.css') }}">
	<link rel="stylesheet" href="{{ asset('app-asset/css/owl.theme.css') }}">
	<!-- Flexslider -->
	<link rel="stylesheet" href="{{ asset('app-asset/css/flexslider.css') }}">
	<!-- Flexslider -->
	<link rel="stylesheet" href="{{ asset('app-asset/css/cd-hero.css') }}">
	<!-- Style Swicther -->
	<link id="style-switch" href="{{ asset('app-asset/css/presets/preset3.css') }}" media="screen" rel="stylesheet" type="text/css">
	<!-- Cokies -->
    <link rel="stylesheet" href="{{asset('app-asset/demo.css')}}">
    <script src="{{asset('app-asset/demo.js')}}" defer></script>
    <!-- Load plugin css -->
    <!-- <link rel="stylesheet" href="../../dist/cookieconsent.css"> -->
    <!-- Optimized css loading -->
    <link rel="stylesheet" href="{{asset('app-asset/cookieconsent.css')}}" media="print" onload="this.media='all'">

    <!-- ReCaptcha -->
    <script src="https://www.google.com/recaptcha/api.js"></script>

	<!-- HTML5 shim, for IE6-8 support of HTML5 elements. All other JS at the end of file. -->
    <!--[if lt IE 9]>
      <script src="js/html5shiv.js"></script>
      <script src="js/respond.min.js"></script>
    <![endif]-->
</head>

<body>

	<div class="style-switch-wrapper">
		<div class="style-switch-button">
			<i class="fa-solid fa-globe"></i>
		</div>
        @if(count($langs) != 1)
            @foreach ($langs as $lang)
                @if ($lang->is_active)
                <a href="{{ route('change-locale', [$lang->lang_code]) }}">
                    <img src="{{ asset($lang->icon) }}" alt="" style="margin:5%; width:32px!important; height:32px!important;">
                </a>
                @endif
            @endforeach
        @endif
		<br/><br/>
		<a class="btn btn-sm btn-primary close-styler pull-right">{{$constant->close_lang}}</a>
	</div>

	<div class="body-inner">

	<!-- Header start -->
	<header id="header" class="navbar-fixed-top header" role="banner">
		<div class="container">
			<div class="row">
				<!-- Logo start -->
				<div class="navbar-header">
				   <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
				        <span class="sr-only">Toggle navigation</span>
				        <span class="icon-bar"></span>
				        <span class="icon-bar"></span>
				        <span class="icon-bar"></span>
				    </button>
				    <div class="navbar-brand navbar-bg">
                        @mobile
                        @if ($header->logo_small_screen)
                        <a href="{{ route('slug', ['/']) }}"><img src="{{ asset($header->logo_small_screen) }}" class="img-fluid" alt=""></a>
                        @elseif ($header->logo_large_screen)
                        <a href="{{ route('slug', ['/']) }}"><img src="{{ asset($header->logo_large_screen) }}" class="img-fluid" alt=""></a>
                        @else
                        <h1 class="logo"><a href="{{ route('slug', ['/']) }}">{{ $header->title }}<span></span></a></h1>
                        @endif
                        @endmobile
                        @tablet
                        @if ($header->logo_large_screen)
                        <a href="{{ route('slug', ['/']) }}"><img src="{{ asset($header->logo_large_screen) }}" class="img-fluid" alt=""></a>
                        @elseif ($header->logo_small_screen)
                        <a href="{{ route('slug', ['/']) }}"><img src="{{ asset($header->logo_small_screen) }}" class="img-fluid" alt=""></a>
                        @else
                        <h1 class="logo"><a href="{{ route('slug', ['/']) }}">{{ $header->title }}<span></span></a></h1>
                        @endif
                        @endtablet
                        @desktop
                        @if ($header->logo_large_screen)
                        <a href="{{ route('slug', ['/']) }}"><img src="{{ asset($header->logo_large_screen) }}" class="img-fluid" alt=""></a>
                        @elseif ($header->logo_small_screen)
                        <a href="{{ route('slug', ['/']) }}"><img src="{{ asset($header->logo_small_screen) }}" class="img-fluid" alt=""></a>
                        @else
                        <h1 class="logo"><a href="{{ route('slug', ['/']) }}">{{ $header->title }}<span></span></a></h1>
                        @endif
                        @enddesktop
				    </div>
				</div><!--/ Logo end -->

                <nav class="collapse navbar-collapse clearfix" role="navigation">
                    <ul class="nav navbar-nav navbar-right">
                        @foreach ($menus as $menu)
                        @mobile
                          @if ($menu->rank == 1 && $menu->is_mobile_active)
                            <!-- Alt kategorisi var mı -->
                            @php $hasSubMenu = false; @endphp
                            @foreach ($menus as $item)
                              @if ($item->parent_menu_uid == $menu->uid)
                                @php $hasSubMenu = true; @endphp
                                @break
                              @endif
                            @endforeach
                            <!-- Alt kategorisi var mı -->
                            @if ($hasSubMenu)
                            <li class="dropdown"><a href="{{ route('slug', [$menu->content_slug]) }}" class="dropdown-toggle" data-toggle="dropdown"><span>{{ $menu->menu_name }}</span> <i class="fa fa-angle-down"></i></a>
                              <div class="dropdown-menu">
                                  <ul>
                                  @foreach ($menus as $item)
                                      @if ($item->parent_menu_uid == $menu->uid && $item->is_mobile_active)
                                      <li class="dropdown"><a href="{{ route('slug', [$item->content_slug]) }}" class="dropdown-toggle" data-toggle="dropdown"><span>{{ $item->menu_name }}</span> <i class="fa fa-angle-right"></i></a>
                                      <ul>
                                          @foreach ($menus as $subitem)
                                          @if ($subitem->parent_menu_uid == $item->uid && $subitem->is_mobile_active)
                                          <li><a href="{{ route('slug', [$subitem->content_slug]) }}">{{ $subitem->menu_name }}</a></li>
                                          @endif
                                          @endforeach
                                      </ul>
                                      </li>
                                      @endif
                                  @endforeach
                                  </ul>
                              </div>
                            </li>
                            @else
                            <li><a class="nav-link scrollto" href="{{ route('slug', [$menu->content_slug]) }}">{{ $menu->menu_name }}</a></li>
                            @endif
                          @endif
                        @endmobile
                        @tablet
                          @if ($menu->rank == 1 && $menu->is_desktop_active)
                            <!-- Alt kategorisi var mı -->
                            @php $hasSubMenu = false; @endphp
                            @foreach ($menus as $item)
                              @if ($item->parent_menu_uid == $menu->uid)
                                @php $hasSubMenu = true; @endphp
                                @break
                              @endif
                            @endforeach
                            <!-- Alt kategorisi var mı -->
                            @if ($hasSubMenu)
                            <li class="dropdown"><a href="{{ route('slug', [$menu->content_slug]) }}" class="dropdown-toggle" data-toggle="dropdown"><span>{{ $menu->menu_name }}</span> <i class="fa fa-angle-down"></i></a>
                              <div class="dropdown-menu">
                                  <ul>
                                  @foreach ($menus as $item)
                                      @if ($item->parent_menu_uid == $menu->uid && $item->is_desktop_active)
                                      <li class="dropdown"><a href="{{ route('slug', [$item->content_slug]) }}" class="dropdown-toggle" data-toggle="dropdown"><span>{{ $item->menu_name }}</span> <i class="fa fa-angle-right"></i></a>
                                      <ul>
                                          @foreach ($menus as $subitem)
                                          @if ($subitem->parent_menu_uid == $item->uid && $subitem->is_desktop_active)
                                          <li><a href="{{ route('slug', [$subitem->content_slug]) }}">{{ $subitem->menu_name }}</a></li>
                                          @endif
                                          @endforeach
                                      </ul>
                                      </li>
                                      @endif
                                  @endforeach
                                  </ul>
                              </div>
                            </li>
                            @else
                            <li><a class="nav-link scrollto" href="{{ route('slug', [$menu->content_slug]) }}">{{ $menu->menu_name }}</a></li>
                            @endif
                          @endif
                        @endtablet
                        @desktop
                          @if ($menu->rank == 1 && $menu->is_desktop_active)
                            <!-- Alt kategorisi var mı -->
                            @php $hasSubMenu = false; @endphp
                            @foreach ($menus as $item)
                              @if ($item->parent_menu_uid == $menu->uid)
                                @php $hasSubMenu = true; @endphp
                                @break
                              @endif
                            @endforeach
                            <!-- Alt kategorisi var mı -->
                            @if ($hasSubMenu)
                            <li class="dropdown"><a href="{{ route('slug', [$menu->content_slug]) }}" class="dropdown-toggle" data-toggle="dropdown"><span>{{ $menu->menu_name }}</span> <i class="fa fa-angle-down"></i></a>
                              <div class="dropdown-menu">
                                  <ul>
                                  @foreach ($menus as $item)
                                      @if ($item->parent_menu_uid == $menu->uid && $item->is_desktop_active)
                                      <li class="dropdown"><a href="{{ route('slug', [$item->content_slug]) }}" class="dropdown-toggle" data-toggle="dropdown"><span>{{ $item->menu_name }}</span> <i class="bi bi-chevron-right"></i></a>
                                      <ul>
                                          @foreach ($menus as $subitem)
                                          @if ($subitem->parent_menu_uid == $item->uid && $subitem->is_desktop_active)
                                          <li><a href="{{ route('slug', [$subitem->content_slug]) }}">{{ $subitem->menu_name }}</a></li>
                                          @endif
                                          @endforeach
                                      </ul>
                                      </li>
                                      @endif
                                  @endforeach
                                  </ul>
                              </div>
                            </li>
                            @else
                            <li><a class="nav-link scrollto" href="{{ route('slug', [$menu->content_slug])}}">{{ $menu->menu_name }}</a></li>
                            @endif
                          @endif
                        @enddesktop
                        @endforeach
                    </ul>
                </nav><!--/ Navigation end -->
			</div><!--/ Row end -->
		</div><!--/ Container end -->
	</header><!--/ Header end -->
    <!--
       Cihaz türü için session ataması
    -->
    @mobile
    @php
      session(['is_desktop' => false]);
    @endphp
    @endmobile
    @tablet
    @php
      session(['is_desktop' => false]);
    @endphp
    @endtablet
    @desktop
    @php
      session(['is_desktop' => true]);
    @endphp
    @enddesktop

