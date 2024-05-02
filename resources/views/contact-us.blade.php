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
                    <li><a href="">{{ $content->title }}</a></li>
                  </ul>
              </div>
          </div><!-- Subpage title end -->
</div><!-- Banner area end -->

<!-- Main container start -->

<section id="main-container">
    <div class="container">
        <div class="row">
            <div class="col-md-7">
                <form id="contact-form" action="{{ route('contact-us.store') }}" method="POST">
                    @csrf
                    @if (session('success'))
                    <div class="alert alert-success" role="alert">
                      <i class="fas fa-check-circle"></i> {{ $constant->sent_message_success }}
                    </div>
                    @endif
                    @error('name')
                    <div class="alert alert-danger">{{ $constant->sent_name_error }}</div>
                    @enderror
                    @error('email')
                    <div class="alert alert-danger">{{ $constant->sent_mail_error }}</div>
                    @enderror
                    @error('subject')
                    <div class="alert alert-danger">{{ $constant->sent_subject_error }}</div>
                    @enderror
                    @error('context')
                    <div class="alert alert-danger">{{ $constant->sent_message_error }}</div>
                    @enderror
                    @error('_token')
                    <div class="alert alert-danger">{{ $constant->sent_validation_error }}</div>
                    @enderror
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ $constant->contact_name }}*</label>
                            <input class="form-control" name="name" id="name" placeholder="" type="text" required="" value="{{old('name')}}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ $constant->contact_mail }}*</label>
                                <input class="form-control" name="email" id="email" placeholder="" type="email"  required="" value="{{old('email')}}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ $constant->subject }}*</label>
                                <input class="form-control" name="subject" id="subject" placeholder="" type="text"  required="" value="{{old('subject')}}">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>{{ $constant->message }}*</label>
                        <textarea class="form-control" name="context" id="context" placeholder="" rows="10"  required="" value="{{old('context')}}"></textarea>
                    </div>
                    <div class="text-right">
                        <button class="g-recaptcha btn btn-primary solid blank sendmail"
                                data-sitekey="6LeyQ34bAAAAAHb7l_haAYETR83OXt3DKiY9ZOwD"
                                data-callback='onSubmit'
                                data-action='submit'
                                type="submit"
                                name="sendmail"
                                id="sendmail" style="padding-left: 16%; padding-right: 16%;">
                                {{ $constant->send_button }}
                        </button>
                    </div>
                </form>
            </div>
            <div class="col-md-5">
                <div class="contact-info">
                    <h3>{{ $content->subtitle }}</h3>
                    <p>{!! $content->description !!}</p>
                    <p>{!! $content->short_description !!}</p>
                    <br>
                    <p><i class="fa fa-home info"></i>  {{ $content->address }} </p>
                    <p><i class="fa fa-phone info"></i>  <a style="color:rgb(100, 100, 100)" href="tel:{{ $contact->phone1 }}">{{ $contact->phone1 }}</a> </p>
                    <p><i class="fa fa-envelope-o info"></i>  <a style="color:rgb(100, 100, 100)" href="mailto:{{ $contact->email }}">{{ $contact->email }}</a></p>
                </div>
            </div>
        </div>
        <div class="gap-40"></div>
        <div class="row">
            <!-- Map start here -->
            <div id="map-wrapper" class="no-padding" style="margin-top: 4%; margin-bottom:8%;">
                <div class="map" id="map">
                    <div class="mapouter">
                        {!! $content->google_maps !!}
                    </div>
                </div>
            </div><!--/ Map end here -->

        </div><!-- Content row  end -->

    </div><!--/ container end -->

</section><!--/ Main container end -->

<script>
    function onSubmit(token) {
      document.getElementById("contact-form").submit();
    }
</script>

@endsection
