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
<main id="main">

	<div id="banner-area">
		<img src="{{ asset($content->hero) }}" alt ="" />
		<div class="parallax-overlay"></div>
			<!-- Subpage title start -->
			<div class="banner-title-content">
	        	<div class="text-center">
		        	<h2>{{ $content->title }}</h2>
		        	<ul class="breadcrumb">
			            <li><span href="{{ route('slug', [$homePageMenu->content_slug]) }}">{{ $homePageMenu->menu_name }}</span></li>
			            <li><a href="{{ route('slug', [$content->slug]) }}">{{ $content->title }}</a></li>
		          	</ul>
	          	</div>
          	</div><!-- Subpage title end -->
	</div><!-- Banner area end -->

    <!-- ======= About Section ======= -->
    <section id="about" class="about">
      <div class="container" data-aos="fade-up">

        <div class="section-title">
          <h2>{{ $content->title }}</h2>
          <h3>{{ $content->subtitle }} <span></span></h3>
          <p>{!! $content->short_description !!}</p>
        </div>

        <div class="row">
          <div class="col-lg-6" data-aos="fade-right" data-aos-delay="100">
            @mobile
            @if ($content->image_small_screen)
            <img src="{{ asset($content->image_small_screen) }}" class="img-fluid" alt="">
            @else
            <img src="{{ asset($content->image_large_screen) }}" class="img-fluid" alt="">
            @endif
            @endmobile
            @tablet
            @if ($content->image_large_screen)
            <img src="{{ asset($content->image_large_screen) }}" class="img-fluid" alt="">
            @else
            <img src="{{ asset($content->image_small_screen) }}" class="img-fluid" alt="">
            @endtablet
            @endif
            @desktop
            @if ($content->image_large_screen)
            <img src="{{ asset($content->image_large_screen) }}" class="img-fluid" alt="">
            @else
            <img src="{{ asset($content->image_small_screen) }}" class="img-fluid" alt="">
            @endif
            @enddesktop
          </div>
          <div class="col-lg-6 pt-4 pt-lg-0 content d-flex flex-column justify-content-center" data-aos="fade-up" data-aos-delay="100">
            <p>{!! $content->description !!}</p>

          </div>
        </div>

      </div>
    </section><!-- End About Section -->

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

      <section class="section-bg p-0" style="height: 64px;"></section>
</main>
@endsection
