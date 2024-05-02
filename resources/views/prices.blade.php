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
                <h2>{{ $content->title }}</h2>
                <ul class="breadcrumb">
                    <li><span href="{{ route('slug', [$homePageMenu->content_slug]) }}">{{ $homePageMenu->menu_name }}</span></li>
                    <li><a href="#">{{ $content->title }}</a></li>
                  </ul>
              </div>
          </div><!-- Subpage title end -->
</div><!-- Banner area end -->

<!-- Main container start -->

<section id="main-container">
    <div class="container">
          <div class="row">
            @php $counter=0; @endphp
            @foreach ($prices as $price)
                @if ($counter == 0)
                <!-- Pricing table start -->
                </div>
                <div class="row">
                <div class="row" style="margin-top: 5%;">
                    <div class="col-md-12 heading">
                        <span class="title-icon classic pull-left"><i class="fa fa-university"></i></span>
                        <h2 class="title classic">{{ $price->keywords }}</h2>
                    </div>
                </div><!-- Title row end -->
                @endif
            <!-- plan start -->
            <div class="col-md-4 col-sm-6 wow fadeInUp" data-wow-delay="1s">
                <div class="plan text-center @if ($counter == 1) featured @endif">
                    <span class="plan-name">{{ $price->title }} <small>{{ $price->subtitle }}</small></span>
                    <p class="plan-price"><strong>{{ $price->pricing_url }}</strong></p>
                    <ul class="list-unstyled">
                        <li>{{ $price->entry1 }}</li>
                        <li>{{ $price->entry2 }}</li>
                        <li>{{ $price->entry3 }}</li>
                        <li>{{ $price->entry4 }}</li>
                        <li>{{ $price->entry5 }}</li>
                    </ul>
                    <a class="btn btn-primary" href="{{ route('doubleSlug', [$content->slug, $price->slug, 'detail' => $price->id]) }}">{{ $content->show_button }}</a>
                </div>
            </div><!-- plan end -->
            @php $counter++; @endphp
            @if ($counter == 3)
            @php $counter=0; @endphp
            @endif
            @endforeach
        </div><!--/ Content row end -->


    </div><!-- container end -->
</section><!--/ Main container end -->


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
