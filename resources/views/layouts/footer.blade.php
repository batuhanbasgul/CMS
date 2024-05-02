	<!--
    <div class="gap-40"></div>
    -->
	<!-- Footer start -->
	<footer id="footer" class="footer">
		<div class="container">
			<div class="row">
				<div class="col-md-4 col-sm-12 footer-widget">
                    @mobile
                    @if ($footer->logo_small_screen)
                    <img src="{{ asset($footer->logo_small_screen) }}" class="img-fluid mb-2" alt="">
                    @elseif ($footer->logo_large_screen)
                    <img src="{{ asset($footer->logo_large_screen) }}" class="img-fluid mb-2" alt="">
                    @endif
                    @endmobile
                    @tablet
                    @if ($footer->logo_large_screen)
                    <img src="{{ asset($footer->logo_large_screen) }}" class="img-fluid mb-2" alt="">
                    @elseif ($footer->logo_small_screen)
                    <img src="{{ asset($footer->logo_small_screen) }}" class="img-fluid mb-2" alt="">
                    @endif
                    @endtablet
                    @desktop
                    @if ($footer->logo_large_screen)
                    <img src="{{ asset($footer->logo_large_screen) }}" class="img-fluid mb-2" alt="">
                    @elseif ($footer->logo_small_screen)
                    <img src="{{ asset($footer->logo_small_screen) }}" class="img-fluid mb-2" alt="">
                    @endif
                    @enddesktop
					<div class="latest-post-items media">
						<div class="latest-post-content media-body">
							<h3 style="color:white">{{ $footer->title }}</h3>
                            <span class="author">{!! $footer->description !!}</span>
						</div>
					</div><!-- 1st Latest Post end -->
				</div><!--/ End Recent Posts-->


				<div class="col-md-4 col-sm-12 footer-widget">
					<h3 class="widget-title">{{ $constant->quickmenu_title_1 }}</h3>
                    @mobile
                    @php ($i=0)
                    @foreach ($menus as $menu)
                        @if ($menu->is_mobile_active)
                        <div class="latest-post-items media">
                            <div class="latest-post-content media-body">
                                <h4><a href="{{ route('slug', [$menu->content_slug]) }}">- {{ $menu->menu_name}}</a></h4>
                            </div>
                        </div>
                        @endif
                        @php ($i++)
                        @if ($i>=5)
                            @break
                        @endif
                    @endforeach
                    @endmobile
                    @tablet
                    @php ($i=0)
                    @foreach ($menus as $menu)
                        @if ($menu->is_desktop_active)
                        <div class="latest-post-items media">
                            <div class="latest-post-content media-body">
                                <h4><a href="{{ route('slug', [$menu->content_slug]) }}">- {{ $menu->menu_name}}</a></h4>
                            </div>
                        </div>
                        @endif
                        @php ($i++)
                        @if ($i>=5)
                            @break
                        @endif
                    @endforeach
                    @endtablet
                    @desktop
                    @php ($i=0)
                    @foreach ($menus as $menu)
                        @if ($menu->is_desktop_active)
                        <div class="latest-post-items media">
                            <div class="latest-post-content media-body">
                                <h4><a href="{{ route('slug', [$menu->content_slug]) }}">- {{ $menu->menu_name}}</a></h4>
                            </div>
                        </div>
                        @endif
                        @php ($i++)
                        @if ($i>=5)
                            @break
                        @endif
                    @endforeach
                    @enddesktop

				</div><!--/ End Recent Posts-->

				<div class="col-md-3 col-sm-12 footer-widget footer-about-us">
					<h3 class="widget-title">{{ $contact->title }}</h3>
					<p>{!! $contact->short_description !!}</p>
					<h4>{{ $constant->address }}</h4>
					<p>{{ $contact->address }}</p>

                    <h4>{{ $constant->email }}</h4>
                    <p><a style="color:white" href="mailto:{{ $contact->email }}">{{ $contact->email }}</a></p>

                    <h4>{{ $constant->phone }}</h4>
                    <p><a style="color:white" href="tel:{{ $contact->phone1 }}">{{ $contact->phone1 }}</a></p>
				</div><!--/ end about us -->

			</div><!-- Row end -->
		</div><!-- Container end -->
	</footer><!-- Footer end -->


	<!-- Copyright start -->
	<section id="copyright" class="copyright angle">
		<div class="container">
			<div class="row">
				<div class="col-md-12 text-center">
					<ul class="footer-social unstyled">
						<li>
                            @if ($header->twitter)
                                <a title="Twitter" href="{{$header->twitter}}">
                                    <span class="icon-pentagon wow bounceIn"><i class="fa fa-twitter"></i></span>
                                </a>
                            @endif
                            @if ($header->facebook)
                                <a title="Facebook" href="{{$header->facebook}}">
                                    <span class="icon-pentagon wow bounceIn"><i class="fa fa-facebook"></i></span>
                                </a>
                            @endif
                            @if ($header->instagram)
							<a title="Instagram" href="{{$header->instagram}}">
								<span class="icon-pentagon wow bounceIn"><i class="fa fa-instagram"></i></span>
							</a>
                            @endif
                            @if ($header->linkedin)
                                <a title="linkedin" href="{{$header->linkedin}}">
                                    <span class="icon-pentagon wow bounceIn"><i class="fa fa-linkedin"></i></span>
                                </a>
                            @endif
                            @if ($header->pinterest)
                                <a title="Pinterest" href="{{$header->pinterest}}">
                                    <span class="icon-pentagon wow bounceIn"><i class="fa fa-pinterest"></i></span>
                                </a>
                            @endif
						</li>
					</ul>
				</div>
			</div><!--/ Row end -->
			<div class="row">
				<div class="col-md-12 text-center">
					<div class="copyright-info">
         			 &copy; {{ $footer->copy_right }} <span>{{ $constant->copyright_description }}</span>
        			</div>
				</div>
			</div><!--/ Row end -->
		   <div id="back-to-top" data-spy="affix" data-offset-top="10" class="back-to-top affix">
				<button class="btn btn-primary" title="Back to Top"><i class="fa fa-angle-double-up"></i></button>
			</div>
		</div><!--/ Container end -->
	</section><!--/ Copyright end -->

	<!-- Javascript Files
	================================================== -->

	<!-- initialize jQuery Library -->
	<script type="text/javascript" src="{{ asset('app-asset/js/jquery.js') }}"></script>
	<!-- Bootstrap jQuery -->
	<script type="text/javascript" src="{{ asset('app-asset/js/bootstrap.min.js') }}"></script>
	<!-- Style Switcher -->
	<script type="text/javascript" src="{{ asset('app-asset/js/style-switcher.js') }}"></script>
	<!-- Owl Carousel -->
	<script type="text/javascript" src="{{ asset('app-asset/js/owl.carousel.js') }}"></script>
	<!-- PrettyPhoto -->
	<script type="text/javascript" src="{{ asset('app-asset/js/jquery.prettyPhoto.js') }}"></script>
	<!-- Bxslider -->
	<script type="text/javascript" src="{{ asset('app-asset/js/jquery.flexslider.js') }}"></script>
	<!-- CD Hero slider -->
	<script type="text/javascript" src="{{ asset('app-asset/js/cd-hero.js') }}"></script>
	<!-- Isotope -->
	<script type="text/javascript" src="{{ asset('app-asset/js/isotope.js') }}"></script>
	<script type="text/javascript" src="{{ asset('app-asset/js/ini.isotope.js') }}"></script>
	<!-- Wow Animation -->
	<script type="text/javascript" src="{{ asset('app-asset/js/wow.min.js') }}"></script>
	<!-- SmoothScroll -->
	<script type="text/javascript" src="{{ asset('app-asset/js/smoothscroll.js') }}"></script>
	<!-- Eeasing -->
	<script type="text/javascript" src="{{ asset('app-asset/js/jquery.easing.1.3.js') }}"></script>
	<!-- Counter -->
	<script type="text/javascript" src="{{ asset('app-asset/js/jquery.counterup.min.js') }}"></script>
	<!-- Waypoints -->
	<script type="text/javascript" src="{{ asset('app-asset/js/waypoints.min.js') }}"></script>
	<!-- Template custom -->
	<script type="text/javascript" src="{{ asset('app-asset/js/custom.js') }}"></script>

    <!-- Cookies -->
    <div class="cookie-data">
        <input type="hidden" id="cookie_title" value="{{ $constant->cookie_title }}">
        <input type="hidden" id="cookie_description" value="{{ $constant->cookie_description }}">
        <input type="hidden" id="link_title" value="{{ $constant->link_title }}">
        <input type="hidden" id="cookie_button" value="{{ $constant->cookie_button }}">
        <input type="hidden" id="cookie_button_refuse" value="{{ $constant->cookie_button_refuse }}">
    </div>

    <script defer src="{{asset('app-asset/cookieconsent.js')}}"></script>
    <script defer src="{{asset('app-asset/cookieconsent-init.js')}}"></script>
	</div><!-- Body inner end -->
</body>
</html>
