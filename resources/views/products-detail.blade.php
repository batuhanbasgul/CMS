@extends('layouts.base')

@section('title')
    {{ $content->title }}
@endsection

@if ($content->keywords)
  @section('keywords')
      {{ $content->keywords }}
  @endsection
@else
@section('keywords')
    {{ $appSetting->keywords }}
@endsection
@endif

@section('content')
<div id="banner-area">
    <img src="{{ asset($content->hero) }}" alt ="" />
    <div class="parallax-overlay"></div>
        <!-- Subpage title start -->
        <div class="banner-title-content">
            <div class="text-center">
                <h2>{{ $content->name }}</h2>
                <ul class="breadcrumb">
                    <li><span href="{{ route('slug', [$homePageMenu->content_slug]) }}">{{ $homePageMenu->menu_name }}</span></li>
                    <li><span href="{{ route('slug', [$productsInfo->slug]) }}">{{ $productsInfo->title }}</span></li>
                    @for ($i=0;$i<count($breadcrumbContents);$i++)
                      @if (0 == $i)
                      <li><span href="{{ route('doubleSlug', [$productsInfo->slug,$breadcrumbContents[$i]->slug]) }}">{{ $breadcrumbContents[$i]->name }}</span></li>
                      @elseif (1==$i)
                      <li><span href="{{ route('tripleSlug', [$productsInfo->slug,$breadcrumbContents[$i-1]->slug,$breadcrumbContents[$i]->slug]) }}">{{ $breadcrumbContents[$i]->name }}</span></li>
                      @elseif (2==$i)
                      <li><span href="{{ route('fourSlug', [$productsInfo->slug,$breadcrumbContents[$i-2]->slug,$breadcrumbContents[$i-1]->slug,$breadcrumbContents[$i]->slug]) }}">{{ $breadcrumbContents[$i]->name }}</span></li>
                      @endif
                    @endfor
                    <li><a>{{ $content->name }}</a></li>
                  </ul>
              </div>
          </div><!-- Subpage title end -->
</div><!-- Banner area end -->


<!-- Portfolio item start -->
<section id="portfolio-item">
    <div class="container">
        <!-- Portfolio item row start -->
        <div class="row">
            <!-- Portfolio item slider start -->
            <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                <div class="portfolio-slider">
                    <div class="flexportfolio flexslider">
                        <ul class="slides" style="max-width: 550px; margin-top:5%; margin-bottom:5%;">
                            @mobile
                            @if($content->thumbnail2_small_screen)
                            <li><img src="{{ asset($content->thumbnail2_small_screen) }}" alt=""></li>
                            @elseif ($content->thumbnail_small_screen)
                            <li><img src="{{ asset($item->thumbnail_small_screen) }}" alt=""></li>
                            @elseif($content->thumbnail2_large_screen)
                            <li><img src="{{ asset($content->thumbnail2_large_screen) }}" alt=""></li>
                            @else
                            <li><img src="{{ asset($content->thumbnail_large_screen) }}" alt=""></li>
                            @endif
                            @endmobile
                            @tablet
                            @if($content->thumbnail2_large_screen)
                            <li><img src="{{ asset($content->thumbnail2_large_screen) }}" alt=""></li>
                            @elseif ($content->thumbnail_large_screen)
                            <li><img src="{{ asset($content->thumbnail_large_screen) }}" alt=""></li>
                            @elseif($content->thumbnail2_small_screen)
                            <li><img src="{{ asset($content->thumbnail2_small_screen) }}" alt=""></li>
                            @else
                            <li><img src="{{ asset($content->thumbnail_small_screen) }}" alt=""></li>
                            @endif
                            @endtablet
                            @desktop
                            @if($content->thumbnail2_large_screen)
                            <li><img src="{{ asset($content->thumbnail2_large_screen) }}" alt=""></li>
                            @elseif ($content->thumbnail_large_screen)
                            <li><img src="{{ asset($content->thumbnail_large_screen) }}" alt=""></li>
                            @elseif($content->thumbnail2_small_screen)
                            <li><img src="{{ asset($content->thumbnail2_small_screen) }}" alt=""></li>
                            @else
                            <li><img src="{{ asset($content->thumbnail_small_screen) }}" alt=""></li>
                            @endif
                            @enddesktop
                            @foreach ($images as $image)
                            <li><img src="{{ asset($image->image) }}" alt=""></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            <!-- Portfolio item slider end -->

            <!-- sidebar start -->
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <div class="sidebar">
                    <div class="portfolio-desc">
                        <h3 class="widget-title">{{ $content->name }}</h3>
                        <p>{!! $content->description !!}</p>
                        <br/>
                        <h3 class="widget-title">{{ $constant->product_keywords }}</h3>
                        <p>{{ $content->keywords }}</p>
                        <p><a href="{{ $content->product_url }}" class="project-btn btn btn-primary" target="_blank">{{ $constant->product_price }}</a></p>
                    </div>
                </div>
            </div>
            <!-- sidebar end -->
        </div><!-- Portfolio item row end -->
    </div><!-- Container end -->
</section><!-- Portfolio item end -->
@endsection
