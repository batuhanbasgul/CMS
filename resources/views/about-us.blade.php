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

			<!-- Company Profile -->
			<div class="row">
				<div class="col-md-12 heading">
					<span class="title-icon classic pull-left"><i class="fa-solid fa-address-card"></i></span>
					<h2 class="title classic">{{$content->title}}</h2>
				</div>
			</div><!-- Title row end -->

			<div class="row">
				<div class="landing-tab clearfix">
					<ul class="nav nav-tabs nav-stacked col-md-3 col-sm-5">
                        @php $isFirst = true; $tabCounter = 0; $limit=0;@endphp
                        @foreach ($aboutusCards as $card)
                        @if($limit <= $content->card_limit)
                            @if ($isFirst)
                            <li class="active">
                                <a class="animated fadeIn" href="#{{$tabCounter}}" data-toggle="tab">
                                    <span class="tab-icon"><i class="{{ $card->icon }}"></i></span>
                                    <div class="tab-info">
                                        <h3>{{ $card->title }}</h3>
                                    </div>
                                </a>
                            </li>
                            @php $isFirst = false; $limit++; @endphp
                            @else
                            <li>
                                <a class="animated fadeIn" href="#{{$tabCounter}}" data-toggle="tab">
                                    <span class="tab-icon"><i class="{{ $card->icon }}"></i></span>
                                    <div class="tab-info">
                                        <h3>{{ $card->title }}</h3>
                                    </div>
                                </a>
                            </li>
                            @endif
                            @php $tabCounter++; $limit++; @endphp
                        @endif
                        @endforeach
					</ul>
					<div class="tab-content col-md-9 col-sm-7">
                        @php $isFirst = true; $tabCounter = 0; $limit = 0; @endphp
                        @foreach ($aboutusCards as $card)
                        @if($limit <= $content->card_limit)
                            @if ($isFirst)
                            <div class="tab-pane active animated fadeInRight" id="{{$tabCounter}}">
                                @mobile
                                @if ($card->image_small_screen)
                                <img src="{{ asset($card->image_small_screen) }}" alt="">
                                @else
                                <img src="{{ asset($card->image_large_screen) }}" alt="">
                                @endif
                                @endmobile
                                @tablet
                                @if ($card->image_large_screen)
                                <img src="{{ asset($card->image_large_screen) }}" alt="">
                                @else
                                <img src="{{ asset($card->image_small_screen) }}" alt="">
                                @endif
                                @endtablet
                                @desktop
                                @if ($card->image_large_screen)
                                <img src="{{ asset($card->image_large_screen) }}" alt="">
                                @else
                                <img src="{{ asset($card->image_small_screen) }}" alt="">
                                @endif
                                @enddesktop
                                <h3 style="margin-top: 5%">{{ $card->subtitle }}</h3>
                                <p>{!! $card->description !!}</p>
                            </div>
                            @php $isFirst = false; $limit++; @endphp
                            @else
                            <div class="tab-pane animated fadeInLeft" id="{{$tabCounter}}">
                                @mobile
                                @if ($card->image_small_screen)
                                <img src="{{ asset($card->image_small_screen) }}" alt="">
                                @else
                                <img src="{{ asset($card->image_large_screen) }}" alt="">
                                @endif
                                @endmobile
                                @tablet
                                @if ($card->image_large_screen)
                                <img src="{{ asset($card->image_large_screen) }}" alt="">
                                @else
                                <img src="{{ asset($card->image_small_screen) }}" alt="">
                                @endif
                                @endtablet
                                @desktop
                                @if ($card->image_large_screen)
                                <img src="{{ asset($card->image_large_screen) }}" alt="">
                                @else
                                <img src="{{ asset($card->image_small_screen) }}" alt="">
                                @endif
                                @enddesktop
                                <h3 style="margin-top: 5%">{{ $card->subtitle }}</h3>
                                <p>{!! $card->description !!}</p>
                            </div>
                            @endif
                            @php $tabCounter++; $limit++; @endphp
                        @endif
                        @endforeach
					</div><!-- tab content -->
	    		</div><!-- Overview tab end -->
			</div><!--/ Content row end -->

			<!-- Company Profile -->

		</div><!--/ 1st container end -->


		<div class="gap-60"></div>


		<!-- Counter Strat -->
		<div class="ts_counter_bg parallax parallax2">
			<div class="parallax-overlay"></div>
			<div class="container">
				<div class="row wow fadeInLeft text-center">
                    <!--
					<div class="facts col-md-3 col-sm-6">
						<span class="facts-icon"><i class="fa fa-user"></i></span>
						<div class="facts-num">
							<span class="counter">1200</span>
						</div>
						<h3>Clients</h3>
					</div>

					<div class="facts col-md-3 col-sm-6">
						<span class="facts-icon"><i class="fa fa-institution"></i></span>
						<div class="facts-num">
							<span class="counter">1277</span>
						</div>
						<h3>Item Sold</h3>
					</div>

					<div class="facts col-md-3 col-sm-6">
						<span class="facts-icon"><i class="fa fa-suitcase"></i></span>
						<div class="facts-num">
							<span class="counter">869</span>
						</div>
						<h3>Projects</h3>
					</div>

					<div class="facts col-md-3 col-sm-6">
						<span class="facts-icon"><i class="fa fa-trophy"></i></span>
						<div class="facts-num">
							<span class="counter">76</span>
						</div>
						<h3>Awwards</h3>
					</div>

					<div class="gap-40"></div>

					<div><a href="#" class="btn btn-primary solid">See Our Portfolio</a></div>
                    -->
				</div><!--/ row end -->
			</div><!--/ Container end -->
		</div><!--/ Counter end -->

		<div class="gap-60"></div>

		<div class="container">

			<!-- Company Profile -->

			<div class="row">
				<div class="col-md-12 heading text-center">
					<h2 class="title2">{{$content->title}}
						<span class="title-desc">{!! $content->short_description !!}</span>
					</h2>
				</div>
			</div><!-- Title row end -->

			<div class="row about-wrapper-bottom">

              @mobile
              @if ($content->image_small_screen)
              <div class="col-md-6 ts-padding about-img" style="height:374px;background:url({{ asset($content->image_small_screen) }}) 50% 50% / cover no-repeat;">
              @else
              <div class="col-md-6 ts-padding about-img" style="height:374px;background:url({{ asset($content->image_large_screen) }}) 50% 50% / cover no-repeat;">
              @endif
              @endmobile
              @tablet
              @if ($content->image_large_screen)
              <div class="col-md-6 ts-padding about-img" style="height:374px;background:url({{ asset($content->image_large_screen) }}) 50% 50% / cover no-repeat;">
              @else
              <div class="col-md-6 ts-padding about-img" style="height:374px;background:url({{ asset($content->image_small_screen) }}) 50% 50% / cover no-repeat;">
              @endtablet
              @endif
              @desktop
              @if ($content->image_large_screen)
              <div class="col-md-6 ts-padding about-img" style="height:374px;background:url({{ asset($content->image_large_screen) }}) 50% 50% / cover no-repeat;">
              @else
              <div class="col-md-6 ts-padding about-img" style="height:374px;background:url({{ asset($content->image_small_screen) }}) 50% 50% / cover no-repeat;">
              @endif
              @enddesktop
				</div><!--/ About image end -->
				<div class="col-md-6 ts-padding about-message">
					<h3>{{$content->subtitle}}</h3>
					<p>{!! $content->description !!}</p>
				</div><!--/ About message end -->
			</div><!--/ Content row end -->

			<!-- Company Profile -->

		</div><!--/ 1st container end -->

    <div class="container mt-4" data-aos="fade-up">

        <div class="row">

          <div class="col-lg-12 entries">

            <article class="entry entry-single">

              <!-- ======= Portfolio Section ======= -->
              <section id="portfolio" class="portfolio">

                  <div class="container p-0" data-aos="fade-up">

                    <h2 class="entry-title mb-4">
                      <a href="#">{{ $content->title }}</a>
                    </h2>

                  <div class="row gy-4 portfolio-container" data-aos="fade-up" data-aos-delay="200">

                    @foreach ($images as $image)
                    <div class="col-lg-4 col-md-6 portfolio-item filter-app">
                        <a href="{{ asset($image->image) }}" data-gallery="portfolioGallery" class="portfokio-lightbox" title="{{ $image->title }}">
                            <div class="portfolio-wrap">
                                <img src="{{ asset($image->thumbnail) }}" class="img-fluid" alt="">
                                <div class="portfolio-info">
                                    <h4>{{ $image->title }}</h4>
                                    <p>{{ $image->description }}</p>
                                </div>
                            </div>
                        </a>
                    </div>
                    @endforeach

                  </div>

                  </div>

              </section><!-- End Portfolio Section -->

            </article><!-- End blog entry -->

          </div><!-- End blog entries list -->

        </div>

      </div>

	</section><!--/ Main container end -->
@endsection
