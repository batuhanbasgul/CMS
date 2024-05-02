@extends('layouts.base')

@section('title')
    {{ $price->title }}
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
                <h2>{{ $price->title }}</h2>
                <ul class="breadcrumb">
                    <li><span href="{{ route('slug', [$content->slug]) }}">{{ $content->title }}</span></li>
                    <li><a>{{ $price->title }}</a></li>
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
                @mobile
                @if ($price->image_small_screen)
                <img src="{{ asset($price->image_small_screen) }}" alt="">
                @else
                <img src="{{ asset($price->image_large_screen) }}" alt="">
                @endif
                @endmobile
                @tablet
                @if ($price->image_large_screen)
                <img src="{{ asset($price->image_large_screen) }}" alt="">
                @else
                <img src="{{ asset($price->image_small_screen) }}" alt="">
                @endif
                @endtablet
                @desktop
                @if ($price->image_large_screen)
                <img src="{{ asset($price->image_large_screen) }}" alt="">
                @else
                <img src="{{ asset($price->image_small_screen) }}" alt="">
                @endif
                @enddesktop
            </div>
            <!-- Portfolio item slider end -->

            <!-- sidebar start -->
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <div class="sidebar">
                    <div class="portfolio-desc">
                        <h2 class="widget-title">{{ $price->title }}</h2>
                        <h3 class="widget-title">{{ $price->pricing_url }}</h3>
                        <p>{!! $price->description !!}</p>
                        <br/>
                        <h3 class="widget-title">{{ $price->keywords }}</h3>
                        <p>{{ $price->keywords }}</p>
                        <p>{{ $price->entry1 }}</p>
                        <p>{{ $price->entry2 }}</p>
                        <p>{{ $price->entry3 }}</p>
                        <p>{{ $price->entry4 }}</p>
                        <p>{{ $price->entry5 }}</p>
                        <p>{{ $price->entry6 }}</p>
                        <p>{{ $price->entry7 }}</p>
                        <p>{{ $price->entry8 }}</p>
                        <p>{{ $price->entry9 }}</p>
                        <p>{{ $price->entry10 }}</p>
                    </div>
                </div>
            </div>
            <!-- sidebar end -->
        </div><!-- Portfolio item row end -->
    </div><!-- Container end -->
</section><!-- Portfolio item end -->
<!-- Clients start -->
<section id="clients" class="clients">
    <div class="container">
        <div class="row wow fadeInLeft">
          <div id="client-carousel" class="col-sm-12 owl-carousel owl-theme text-center client-carousel">
            @foreach ($references as $platform)
                <figure class="item client_logo">
                <a href="{{ $platform->reference_url }}" target="_blank">
                    <img src="
                    @mobile
                    @if ($platform->thumbnail_small_screen)
                    {{ asset($platform->thumbnail_small_screen) }}
                    @else
                    {{ asset($platform->thumbnail_large_screen) }}
                    @endif
                    @endmobile
                    @tablet
                    @if ($platform->thumbnail_large_screen)
                    {{ asset($platform->thumbnail_large_screen) }}
                    @else
                    {{ asset($platform->thumbnail_small_screen) }}
                    @endif
                    @endtablet
                    @desktop
                    @if ($platform->thumbnail_large_screen)
                    {{ asset($platform->thumbnail_large_screen) }}
                    @else
                    {{ asset($platform->thumbnail_small_screen) }}
                    @endif
                    @enddesktop
                    " alt="client">
                </a>
                </figure>
            @endforeach
          </div><!-- Owl carousel end -->
        </div><!-- Main row end -->
    </div><!--/ Container end -->
</section><!--/ Clients end -->
@endsection
