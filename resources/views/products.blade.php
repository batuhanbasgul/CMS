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
                @if ($selectedCategory)
                <h2>{{ $selectedCategory->name }}</h2>
                @else
                <h2>{{ $constant->all_products }}</h2>
                @endif
                <ul class="breadcrumb">
                    <li><span href="{{ route('slug', [$homePageMenu->content_slug]) }}">{{ $homePageMenu->menu_name }}</span></li>
                    @if (0 == count($breadcrumbContents))
                    <li><a href="{{ route('slug', [$content->slug]) }}">{{ $content->title }}</a></li>
                    @else
                    <li><span href="{{ route('slug', [$content->slug]) }}">{{ $content->title }}</span></li>
                    @endif
                    @for ($i=0;$i<count($breadcrumbContents);$i++)
                      @if (0 == $i)
                      <li>
                        @if ($i+1 == count($breadcrumbContents))
                        <a href="{{ route('doubleSlug', [$content->slug,$breadcrumbContents[$i]->slug]) }}">{{ $breadcrumbContents[$i]->name }}</a>
                        @else
                        <span href="{{ route('doubleSlug', [$content->slug,$breadcrumbContents[$i]->slug]) }}">{{ $breadcrumbContents[$i]->name }}</span>
                        @endif
                      </li>
                      @elseif (1==$i)
                      <li>
                        @if ($i+1 == count($breadcrumbContents))
                        <a href="{{ route('tripleSlug', [$content->slug,$breadcrumbContents[$i-1]->slug,$breadcrumbContents[$i]->slug]) }}">{{ $breadcrumbContents[$i]->name }}</a>
                        @else
                        <span href="{{ route('tripleSlug', [$content->slug,$breadcrumbContents[$i-1]->slug,$breadcrumbContents[$i]->slug]) }}">{{ $breadcrumbContents[$i]->name }}</span>
                        @endif
                      </li>
                      @elseif (2==$i)
                      <li>
                        @if ($i+1 == count($breadcrumbContents))
                        <a href="{{ route('fourSlug', [$content->slug,$breadcrumbContents[$i-2]->slug,$breadcrumbContents[$i-1]->slug,$breadcrumbContents[$i]->slug]) }}">{{ $breadcrumbContents[$i]->name }}</a>
                        @else
                        <span href="{{ route('fourSlug', [$content->slug,$breadcrumbContents[$i-2]->slug,$breadcrumbContents[$i-1]->slug,$breadcrumbContents[$i]->slug]) }}">{{ $breadcrumbContents[$i]->name }}</span>
                        @endif
                      </li>
                      @endif
                    @endfor
                  </ul>
              </div>
          </div><!-- Subpage title end -->
</div><!-- Banner area end -->


	<!-- Portfolio start -->
	<section id="main-container" class="portfolio portfolio-box">
		<div class="container">
			<!--Isotope filter start -->
			<div class="row text-center">
				<div class="isotope-nav" data-isotope-nav="isotope">
					<ul>
                        <li><a href="#" class="active" data-filter="*">{{$productsInfo->title}}</a></li>
                        @foreach ($productCategories as $item)
                            @if ($item->is_active)
                            <li><a href="#" data-filter=".{{$item->id}}">{{$item->name}}</a></li>
                            @endif
                        @endforeach
					</ul>
				</div>
			</div><!-- Isotope filter end -->

			<div class="row">
				<div id="isotope" class="isotope">
                    @if(0 != count($products))
                    @foreach ($products as $item)
                    <div class="col-sm-3 isotope-item
                    @foreach ($pivots as $pivot)
                        @if ($item->id == $pivot->product_id)
                            {{$pivot->category_id . " "}}
                        @endif
                    @endforeach
                    ">
                        <div class="grid">
                            <a href="
                            @if (count($parentCategories) == 0)
                            {{ route('doubleSlug', [$content->slug, $item->slug, 'detail' => $item->id]) }}
                            @elseif (count($parentCategories) == 1)
                            {{ route('tripleSlug', [$content->slug, $parentCategories[0]->slug, $item->slug, 'detail' => $item->id]) }}
                            @elseif (count($parentCategories) == 2)
                            {{ route('fourSlug', [$content->slug, $parentCategories[1]->slug, $parentCategories[0]->slug, $item->slug, 'detail' => $item->id]) }}
                            @elseif (count($parentCategories) == 3)
                            {{ route('fiveSlug', [$content->slug, $parentCategories[2]->slug, $parentCategories[1]->slug, $parentCategories[0]->slug, $item->slug, 'detail' => $item->id]) }}
                            @endif
                            ">
                                <figure class="effect-oscar">
                                    @mobile
                                    @if($item->thumbnail2_small_screen)
                                    <img src="{{ asset($item->thumbnail2_small_screen) }}" alt="">
                                    @elseif ($item->thumbnail_small_screen)
                                    <img src="{{ asset($item->thumbnail_small_screen) }}" alt="">
                                    @elseif($item->thumbnail2_large_screen)
                                    <img src="{{ asset($item->thumbnail2_large_screen) }}" alt="">
                                    @else
                                    <img src="{{ asset($item->thumbnail_large_screen) }}" alt="">
                                    @endif
                                    @endmobile
                                    @tablet
                                    @if($item->thumbnail2_large_screen)
                                    <img src="{{ asset($item->thumbnail2_large_screen) }}" alt="">
                                    @elseif ($item->thumbnail_large_screen)
                                    <img src="{{ asset($item->thumbnail_large_screen) }}" alt="">
                                    @elseif($item->thumbnail2_small_screen)
                                    <img src="{{ asset($item->thumbnail2_small_screen) }}" alt="">
                                    @else
                                    <img src="{{ asset($item->thumbnail_small_screen) }}" alt="">
                                    @endif
                                    @endtablet
                                    @desktop
                                    @if($item->thumbnail2_large_screen)
                                    <img src="{{ asset($item->thumbnail2_large_screen) }}" alt="">
                                    @elseif ($item->thumbnail_large_screen)
                                    <img src="{{ asset($item->thumbnail_large_screen) }}" alt="">
                                    @elseif($item->thumbnail2_small_screen)
                                    <img src="{{ asset($item->thumbnail2_small_screen) }}" alt="">
                                    @else
                                    <img src="{{ asset($item->thumbnail_small_screen) }}" alt="">
                                    @endif
                                    @enddesktop
                                    <!--
                                    <figcaption>
                                        <a class="link icon-pentagon" href="
                                        @if (count($parentCategories) == 0)
                                        {{ route('doubleSlug', [$content->slug, $item->slug, 'detail' => $item->id]) }}
                                        @elseif (count($parentCategories) == 1)
                                        {{ route('tripleSlug', [$content->slug, $parentCategories[0]->slug, $item->slug, 'detail' => $item->id]) }}
                                        @elseif (count($parentCategories) == 2)
                                        {{ route('fourSlug', [$content->slug, $parentCategories[1]->slug, $parentCategories[0]->slug, $item->slug, 'detail' => $item->id]) }}
                                        @elseif (count($parentCategories) == 3)
                                        {{ route('fiveSlug', [$content->slug, $parentCategories[2]->slug, $parentCategories[1]->slug, $parentCategories[0]->slug, $item->slug, 'detail' => $item->id]) }}
                                        @endif
                                        "><i class="fa fa-link"></i></a>
                                        <a class="view icon-pentagon" data-rel="prettyPhoto" href="images/portfolio/portfolio-bg1.jpg"><i class="fa fa-search"></i></a>
                                    </figcaption>
                                -->
                                </figure>
                            </a>
                            <div class="portfolio-static-desc">
                                <h3><a href="
                                    @if (count($parentCategories) == 0)
                                    {{ route('doubleSlug', [$content->slug, $item->slug, 'detail' => $item->id]) }}
                                    @elseif (count($parentCategories) == 1)
                                    {{ route('tripleSlug', [$content->slug, $parentCategories[0]->slug, $item->slug, 'detail' => $item->id]) }}
                                    @elseif (count($parentCategories) == 2)
                                    {{ route('fourSlug', [$content->slug, $parentCategories[1]->slug, $parentCategories[0]->slug, $item->slug, 'detail' => $item->id]) }}
                                    @elseif (count($parentCategories) == 3)
                                    {{ route('fiveSlug', [$content->slug, $parentCategories[2]->slug, $parentCategories[1]->slug, $parentCategories[0]->slug, $item->slug, 'detail' => $item->id]) }}
                                    @endif
                                    ">{{ $item->name }}</a></h3>
                                <span>{{ $item->product_no }}</span>
                            </div>
                        </div><!--/ grid end -->
                    </div><!--/ item 1 end -->
                    @endforeach
                    @else
                    {{ $constant->no_product }}
                    @endif
				</div><!-- Isotope content end -->
			</div><!-- Content row end -->
		</div><!-- Container end -->
	</section><!-- Portfolio end -->
@endsection
