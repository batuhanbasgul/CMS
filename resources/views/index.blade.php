@extends('layouts.base')

@section('title')
    {{ $appSetting->title }}
@endsection

@section('keywords')
    {{ $appSetting->keywords }}
@endsection

@section('content')

<!-- Slider start -->
<section id="home" class="no-padding">
    <div id="main-slide" class="cd-hero">
        <ul class="cd-hero-slider">
            @php $isFirst = true; @endphp
            @foreach ($sliders as $slider)
                <!-- Hiç resim yoksa slider gösterme -->
                @if (!$slider->image_small_screen && !$slider->image_large_screen)
                    @continue
                @endif
                <!-- Slider pasifse gösterme -->
                @if (!$slider->is_desktop_active && !$slider->is_mobile_active)
                    @continue
                @endif
                <!-- MOBILE USER AGENT -->
                @mobile
                <!-- Slider mobilde pasifse gösterme -->
                @if (!$slider->is_mobile_active)
                    @continue
                @endif
                @if ($slider->image_small_screen)
                    <li @if($isFirst) class="selected" @php $isFirst=false; @endphp @endif>
                        <div class="overlay2">
                            <img class="" src="{{ asset($slider->image_small_screen) }}" alt="slider">
                        </div>
                        <div class="cd-full-width">
                            <h2>{{ $slider->title }}</h2>
                            <h3>{{ $slider->description }}</h3>
                            <a href="{{ $slider->link }}" class="btn btn-primary solid cd-btn">{{ $constant->detail }}</a>
                        </div> <!-- .cd-full-width -->
                    </li>
                @elseif ($slider->image_large_screen)
                    <li @if($isFirst) class="selected" @php $isFirst=false; @endphp @endif>
                        <div class="overlay2">
                            <img class="" src="{{ asset($slider->image_large_screen) }}" alt="slider">
                        </div>
                        <div class="cd-full-width">
                            <h2>{{ $slider->title }}</h2>
                            <h3>{{ $slider->description }}</h3>
                            <a href="{{ $slider->link }}" class="btn btn-primary solid cd-btn">{{ $constant->detail }}</a>
                        </div> <!-- .cd-full-width -->
                    </li>
                @else
                    @continue
                @endif
                @endmobile

                <!-- TABLET USER AGENT -->
                @tablet<!-- Slider mobilde pasifse gösterme -->
                @if (!$slider->is_desktop_active)
                    @continue
                @endif
                @if ($slider->image_large_screen)
                    <li @if($isFirst) class="selected" @php $isFirst=false; @endphp @endif>
                        <div class="overlay2">
                            <img class="" src="{{ asset($slider->image_large_screen) }}" alt="slider">
                        </div>
                        <div class="cd-full-width">
                            <h2>{{ $slider->title }}</h2>
                            <h3>{{ $slider->description }}</h3>
                            <a href="{{ $slider->link }}" class="btn btn-primary solid cd-btn">{{ $constant->detail }}</a>
                        </div> <!-- .cd-full-width -->
                    </li>
                @elseif ($slider->image_small_screen)
                    <li @if($isFirst) class="selected" @php $isFirst=false; @endphp @endif>
                        <div class="overlay2">
                            <img class="" src="{{ asset($slider->image_small_screen) }}" alt="slider">
                        </div>
                        <div class="cd-full-width">
                            <h2>{{ $slider->title }}</h2>
                            <h3>{{ $slider->description }}</h3>
                            <a href="{{ $slider->link }}" class="btn btn-primary solid cd-btn">{{ $constant->detail }}</a>
                        </div> <!-- .cd-full-width -->
                    </li>
                @else
                    @continue
                @endif
                @endtablet
                <!-- DESKTOP USER AGENT -->
                @desktop
                @if ($slider->image_large_screen)
                    <li @if($isFirst) class="selected" @php $isFirst=false; @endphp @endif>
                        <div class="overlay2">
                            <img class="" src="{{ asset($slider->image_large_screen) }}" alt="slider">
                        </div>
                        <div class="cd-full-width">
                            <h2>{{ $slider->title }}</h2>
                            <h3>{{ $slider->description }}</h3>
                            <a href="{{ $slider->link }}" class="btn btn-primary solid cd-btn">{{ $constant->detail }}</a>
                        </div> <!-- .cd-full-width -->
                    </li>
                @elseif ($slider->image_small_screen)
                    <li @if($isFirst) class="selected" @php $isFirst=false; @endphp @endif>
                        <div class="overlay2">
                            <img class="" src="{{ asset($slider->image_small_screen) }}" alt="slider">
                        </div>
                        <div class="cd-full-width">
                            <h2>{{ $slider->title }}</h2>
                            <h3>{{ $slider->description }}</h3>
                            <a href="{{ $slider->link }}" class="btn btn-primary solid cd-btn">{{ $constant->detail }}</a>
                        </div> <!-- .cd-full-width -->
                    </li>
                @else
                    @continue
                @endif
                @enddesktop
            @endforeach
        </ul> <!--/ cd-hero-slider -->

    <div class="cd-slider-nav">
        <nav>
            <span class="cd-marker item-1"></span>
            <ul>
                @php $isFirst = true; @endphp
                @foreach ($sliders as $slider)
                    <!-- Hiç resim yoksa slider gösterme -->
                    @if (!$slider->image_small_screen && !$slider->image_large_screen)
                        @continue
                    @endif
                    <!-- Slider pasifse gösterme -->
                    @if (!$slider->is_desktop_active && !$slider->is_mobile_active)
                        @continue
                    @endif
                    <li @if($isFirst) class="selected" @php $isFirst=false; @endphp @endif><a href="#0"><i class="{{ $slider->logo }}"></i>{{ $slider->logo_title }}</a></li>
                @endforeach
            </ul>
        </nav>
    </div> <!-- .cd-slider-nav -->

    </div><!--/ Main slider end -->
</section> <!--/ Slider end -->

<!-- Service box start -->
<section id="service" class="service angle">
    <div class="container">
        <div class="row">
            <div class="col-md-12 heading">
                <span class="title-icon pull-left"><i class="fa-solid fa-truck-fast"></i></span>
                <h2 class="title">{{ $constant->title }} <span class="title-desc">{{ $constant->subtitle }}</span></h2>
            </div>
        </div><!-- Title row end -->
        <div class="row">
            <div class="col-md-12">
                @foreach ($appCards as $card)
                    <div class="col-md-3 col-sm-3 wow fadeInDown" data-wow-delay=".5s">
                        <div class="service-content text-center">
                            <span class="service-icon icon-pentagon"><i class="{{ $card->icon }}"></i></span>
                            <h3>{{ $card->title }}</h3>
                            <p>{!! $card->description !!}</p>
                        </div>
                    </div><!--/ End first service -->
                @endforeach
            </div>
        </div><!-- Content row end -->
    </div><!--/ Container end -->
</section><!--/ Service box end -->

<!-- Portfolio start -->
<section id="portfolio" class="portfolio">
    <div class="container">
        <div class="row">
            <div class="col-md-12 heading">
                <span class="title-icon classic pull-left"><i class="fa fa-suitcase"></i></span>
                <h2 class="title classic">{{ $productsInfo->title }}</h2>
            </div>
        </div> <!-- Title row end -->

        <!--Isotope filter start -->
        <div class="row text-right">
            <div class="isotope-nav" data-isotope-nav="isotope">
                <ul>
                    <li><a href="#" class="active" data-filter="*">{{$productsInfo->title}}</a></li>
                    @foreach ($productCategories as $category)
                        <li><a href="#" data-filter=".{{$category->id}}">{{$category->name}}</a></li>
                    @endforeach
                </ul>
            </div>
        </div><!-- Isotope filter end -->
    </div>

    <div class="container-fluid">
        <div class="row">
            <div id="isotope" class="isotope">
                @if(0 != count($products))
                @foreach ($products as $item)
                <div class="col-sm-4 isotope-item
                    @foreach ($pivots as $pivot)
                        @if ($item->id == $pivot->product_id)
                            {{$pivot->category_id . " "}}
                        @endif
                    @endforeach
                ">
                    <div class="grid">
                        <a href="{{ route('slug',[$productsInfo->slug]) }}">
                            <figure class="effect-oscar">
                                @mobile
                                @if($item->image_small_screen)
                                <img src="{{ asset($item->image_small_screen) }}" alt="testimonial">
                                @elseif ($item->image_small_screen)
                                <img src="{{ asset($item->image_small_screen) }}" alt="testimonial">
                                @elseif($item->image_large_screen)
                                <img src="{{ asset($item->image_large_screen) }}" alt="testimonial">
                                @else
                                <img src="{{ asset($item->image_large_screen) }}" alt="testimonial">
                                @endif
                                @endmobile
                                @tablet
                                @if($item->image_large_screen)
                                <img src="{{ asset($item->image_large_screen) }}" alt="testimonial">
                                @elseif ($item->image_large_screen)
                                <img src="{{ asset($item->image_large_screen) }}" alt="testimonial">
                                @elseif($item->image_small_screen)
                                <img src="{{ asset($item->image_small_screen) }}" alt="testimonial">
                                @else
                                <img src="{{ asset($item->image_small_screen) }}" alt="testimonial">
                                @endif
                                @endtablet
                                @desktop
                                @if($item->image_large_screen)
                                <img src="{{ asset($item->image_large_screen) }}" alt="testimonial">
                                @elseif ($item->image_large_screen)
                                <img src="{{ asset($item->image_large_screen) }}" alt="testimonial">
                                @elseif($item->image_small_screen)
                                <img src="{{ asset($item->image_small_screen) }}" alt="testimonial">
                                @else
                                <img src="{{ asset($item->image_small_screen) }}" alt="testimonial">
                                @endif
                                @enddesktop
                            </figure>
                        </a>
                    </div>
                </div><!-- Isotope item end -->
                @endforeach
                @else
                {{ $constant->no_product }}
                @endif
            </div><!-- Isotope content end -->
        </div><!-- Content row end -->
    </div><!-- Container end -->
</section><!-- Portfolio end -->

<!-- Feature box start -->
<section id="feature" class="feature">
    <div class="container">
        <div class="row">
            @php $counter = 0; @endphp
            @foreach ($announcements as $announcement)
                <div class="feature-box col-sm-4 wow fadeInDown" data-wow-delay=".5s">
                    <span class="feature-icon pull-left" ><i class="{{ $announcement->author }}"></i></span>
                    <div class="feature-content">
                        <h3>{{ $announcement->title }}</h3>
                        <p>{!! $announcement->short_description !!}</p>
                    </div>
                </div><!--/ End first featurebox -->
                @php $counter++; @endphp
                @if ($counter == 3)
                    <div class="gap-40"></div>
                    @php $counter = 0; @endphp
                @endif
            @endforeach
        </div><!-- Content row end -->
    </div><!--/ Container end -->
</section><!--/ Feature box end -->


<section id="image-block" class="image-block no-padding">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6 ts-padding" style="height:650px;background:url(
                @mobile
                @if ($aboutUs->image_small_screen)
                {{ asset($aboutUs->image_small_screen) }}
                @else
                {{ asset($aboutUs->image_large_screen) }}
                @endif
                @endmobile
                @tablet
                @if ($aboutUs->image_large_screen)
                {{ asset($aboutUs->image_large_screen) }}
                @else
                {{ asset($aboutUs->image_small_screen) }}
                @endif
                @endtablet
                @desktop
                @if ($aboutUs->image_large_screen)
                {{ asset($aboutUs->image_large_screen) }}
                @else
                {{ asset($aboutUs->image_small_screen) }}
                @endif
                @enddesktop
                ) 50% 50% / cover no-repeat;">
            </div>
            <div class="col-md-6 ts-padding img-block-right">
                <div class="img-block-head text-center">
                    <h2>{{$aboutUs->subtitle}}</h2>
                    <h3>{{$aboutUs->title}}</h3>
                    <p>{!! $aboutUs->description !!}</p>
                </div>

                <div class="gap-30"></div>
                @php $counter = 0; @endphp
                @foreach ($aboutUsCards as $card)
                    @if ($counter < $card->limit)
                        <div class="image-block-content">
                            <span class="feature-icon pull-left" ><i class="{{$aboutUs->icon}}"></i></span>
                            <div class="feature-content">
                                <h3>{{$aboutUs->title}}</h3>
                                <p>{{$aboutUs->subtitle}}</p>
                            </div>
                        </div><!--/ End 1st block -->
                        @php $counter++; @endphp
                    @endif
                @endforeach
            </div>
        </div>
    </div>
</section><!--/ Image block end -->

<!-- Parallax 1 start -->
<section class="parallax parallax1">
    <div class="parallax-overlay"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <h2>{{ $constant->buy_title }}</h2>
                <h3>{{ $constant->buy_subtitle }}</h3>
                <p>
                    <a href="{{ route('slug', [$pricingInfoSlug]) }}" class="btn btn-primary white">{{ $constant->buy_price_button }}</a>
                    <a href="{{ route('slug', [$contactSlug]) }}" class="btn btn-primary solid">{{ $constant->buy_contact_button }}</a>
                </p>
            </div>
        </div>
    </div><!-- Container end -->
</section><!-- Parallax 1 end -->
<!--
<section id="pricing" class="pricing">
    <div class="container">
        <div class="row">
            <div class="col-md-12 heading" style="margin: auto">
                <span class="title-icon pull-left"><i class="fa fa-university"></i></span>
                <h2 class="title">{{ $pricingInfo->title }} <span class="title-desc">{{ $pricingInfo->subtitle }}</span></h2>
            </div>
        </div>
          <div class="row">
            @php $field_title = null @endphp
            @foreach ($prices as $price)
                @if ($field_title != $price->keywords)

                <div class="col-md-3 col-sm-6 wow fadeInUp" data-wow-delay="1s">
                    <div class="plan text-center featured">
                        <span class="plan-name">{{ $price->title }} <small>{{ $price->subtitle }}</small></span>
                        <p class="plan-price"><strong>{{ $price->pricing_url}}</strong></p>
                        <ul class="list-unstyled">
                        </ul>
                        <a class="btn btn-primary" href="{{ route('slug', [$pricingInfoSlug]) }}">{{ $pricingInfo->show_button }}</a>
                    </div>
                </div>
                @php $field_title = $price->keywords @endphp
                @endif
            @endforeach
        </div>
    </div>
</section>
-->

	<!-- Testimonial start-->
	<section class="testimonial parallax parallax2">
		<div class="parallax-overlay"></div>
	  	<div class="container">
		    <div class="row">
			    <div id="testimonial-carousel" class="owl-carousel owl-theme text-center testimonial-slide">
                    @foreach ($references as $reference)
			        <div class="item">
			          	<div class="testimonial-thumb">
			            	<img src="
                            @mobile
                            @if ($reference->thumbnail_small_screen)
                            {{ asset($reference->thumbnail_small_screen) }}
                            @else
                            {{ asset($reference->thumbnail_large_screen) }}
                            @endif
                            @endmobile
                            @tablet
                            @if ($reference->thumbnail_large_screen)
                            {{ asset($reference->thumbnail_large_screen) }}
                            @else
                            {{ asset($reference->thumbnail_small_screen) }}
                            @endif
                            @endtablet
                            @desktop
                            @if ($reference->thumbnail_large_screen)
                            {{ asset($reference->thumbnail_large_screen) }}
                            @else
                            {{ asset($reference->thumbnail_small_screen) }}
                            @endif
                            @enddesktop
                            " alt="testimonial">
			          	</div>
			          	<div class="testimonial-content">
				            <p class="testimonial-text">
                                {{$reference->description}}
				            </p>
                            <h3 class="name">{{$reference->title}}<span></span></h3>
			          	</div>
			        </div>
                    @endforeach
			    </div><!--/ Testimonial carousel end-->
		    </div><!--/ Row end-->
	  	</div><!--/  Container end-->
	</section><!--/ Testimonial end-->

<!-- Testimonial start-->
<section class="testimonial parallax parallax2">
    <div class="parallax-overlay"></div>
      <div class="container">
        <div class="row">
            <div id="testimonial-carousel" class="owl-carousel owl-theme text-center testimonial-slide">
                @foreach ($products as $product)
                    <div class="item">
                        <div class="testimonial-thumb">
                            @mobile
                            @if($product->thumbnail2_small_screen)
                            <img src="{{ asset($product->thumbnail2_small_screen) }}" alt="">
                            @elseif ($product->thumbnail_small_screen)
                            <img src="{{ asset($product->thumbnail_small_screen) }}" alt="">
                            @elseif($product->thumbnail2_large_screen)
                            <img src="{{ asset($product->thumbnail2_large_screen) }}" alt="">
                            @else
                            <img src="{{ asset($product->thumbnail_large_screen) }}" alt="">
                            @endif
                            @endmobile
                            @tablet
                            @if($product->thumbnail2_large_screen)
                            <img src="{{ asset($product->thumbnail2_large_screen) }}" alt="">
                            @elseif ($product->thumbnail_large_screen)
                            <img src="{{ asset($product->thumbnail_large_screen) }}" alt="">
                            @elseif($product->thumbnail2_small_screen)
                            <img src="{{ asset($product->thumbnail2_small_screen) }}" alt="">
                            @else
                            <img src="{{ asset($product->thumbnail_small_screen) }}" alt="">
                            @endif
                            @endtablet
                            @desktop
                            @if($product->thumbnail2_large_screen)
                            <img src="{{ asset($product->thumbnail2_large_screen) }}" alt="">
                            @elseif ($product->thumbnail_large_screen)
                            <img src="{{ asset($product->thumbnail_large_screen) }}" alt="">
                            @elseif($product->thumbnail2_small_screen)
                            <img src="{{ asset($product->thumbnail2_small_screen) }}" alt="">
                            @else
                            <img src="{{ asset($product->thumbnail_small_screen) }}" alt="">
                            @endif
                            @enddesktop
                        </div>
                        <div class="testimonial-content">
                            <p>{!! $product->description !!}</p>
                            <h3 class="name">{{ $item->name }}<span>{{ $item->product_no }}</span></h3>
                        </div>
                    </div>
                @endforeach

            </div><!--/ Testimonial carousel end-->
        </div><!--/ Row end-->
      </div><!--/  Container end-->
</section><!--/ Testimonial end-->
@endsection
