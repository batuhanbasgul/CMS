@extends('admin.layouts.base')

@section('title',__('admin_header.update_content'))

@section('content')
<!-- Main Container -->
<div class="page-body pt-5">
  <div class="container-fluid">
    <div class="page-header">
      <div class="row">
        <div class="col-7">
            <div class="row">
                <div class="col-7">
                    <h3>{{__('admin_header.update_content')}} @if(count($langs) != 1)<span class="title-lang-code">({{ request('lang_code') }})</span>@endif</h3>
                    <ol class="breadcrumb">
                      <li class="breadcrumb-item disappear-500"><a href="{{ route('admin.index') }}"><ins>{{__('admin_header.main_page')}}</ins></a></li>
                      <li class="breadcrumb-item disappear-500">{{__('admin_header.header')}}</li>
                      <li class="breadcrumb-item disappear-500">{{__('admin_header.update_header_content')}}</li>
                    </ol>
                </div>
                <div class="col-5">
                    <!--
                      Refresh the page with selected lang_code.
                    -->
                    @if(count($langs) != 1)
                      @foreach ($langs as $lang)
                        @if ($lang_code==$lang->lang_code)
                        <div class="mx-2 d-none">
                          <span class="disabled">({{__('admin_header.current_language')}})</span>
                          <img src="{{ asset($lang->icon) }}" alt="">
                        </div>
                        @endif
                      @endforeach
                      <select class="form-select" id="lang-select" name="lang-select" onchange="window.location.href=this.options[this.selectedIndex].value;">
                          @foreach ($langs as $lang)
                            @if ($lang->is_active)
                            <option value="{{ route('admin.header-settings.index',['lang_code'=>$lang->lang_code, 'page'=>request('page')]) }}"
                                @if ($lang_code==$lang->lang_code)
                                    selected
                                @endif
                            >{{ $lang->lang_name }}</option>
                            @endif
                          @endforeach
                      </select>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-5 d-flex justify-content-end">
        </div>
      </div>
    </div>
  </div>
  <!-- Sweet Alert -->
  @if ($errors->any())
    @error('lang_code')
      <div>
        <script>swal("{{__('admin_header.error')}}", "{{__('admin_header.update_error_context')}}", "error");</script>
      </div>
    @enderror
  @else
    @if(session('success'))
    <script>swal("{{__('admin_header.success')}}", "{{__('admin_header.update_success_context')}}", "success");</script>
    @elseif(session('error'))
    <script>swal("{{__('admin_header.error')}}", "{{__('admin_header.update_error_context')}}", "error");</script>
    @elseif(session('file_extension_error'))
    <script>swal("{{__('admin_header.error')}}", "{{__('admin_header.update_error_file_mime')}}", "error");</script>
    @elseif(session('file_size_error'))
    <script>swal("{{__('admin_header.error')}}", "{{__('admin_header.update_error_file_size')}}", "error");</script>
    @endif
  @endif
  <!-- Container-fluid starts-->
  <div class="container-fluid">
    <div class="card">
      <div class="card-body">
        <ul class="nav nav-tabs border-tab" id="top-tab" role="tablist">
          <li class="nav-item"><a class="nav-link @if (!session()->get('tab_page') || session()->get('tab_page') == 'context') active @endif" data-bs-toggle="tab"
              href="#top-content" role="tab" aria-controls="top-content" aria-selected="true">
              <i class="fas fa-book-open"></i>{{__('admin_header.content')}}</a></li>
          <li class="nav-item"><a class="nav-link @if (session()->get('tab_page') == 'image_large') active @endif" data-bs-toggle="tab"
              href="#top-image-large" role="tab" aria-controls="top-image-large" aria-selected="false">
              <i class="fas fa-desktop"></i>{{__('admin_header.big_image')}}</a></li>
          <li class="nav-item"><a class="nav-link @if (session()->get('tab_page') == 'image_small') active @endif" data-bs-toggle="tab"
              href="#top-image-small" role="tab" aria-controls="top-image-small" aria-selected="false">
              <i class="fas fa-mobile-alt"></i>{{__('admin_header.small_image')}}</a></li>
        </ul>
        <div class="tab-content" id="top-tabContent">
          <div class="tab-pane fade @if (!session()->get('tab_page') || session()->get('tab_page') == 'context') show active @endif" id="top-content" role="tabpanel" aria-labelledby="top-content-tab">
            <form class="theme-form needs-validation" novalidate="" action="{{ route('admin.header-settings.update',[$content->id]) }}" method="POST">
              @csrf
              @method('PUT')
              <input type="hidden" name="page" value="{{ request('page') }}">
              <h4 class="border-bottom pb-2">{{__('admin_header.content')}}</h4>
              <div class="mb-4">
                <label class="form-label p-0" for="title">{{__('admin_header.title')}} </label>
                <input type="text" class="form-control" id="title" name="title" placeholder="{{__('admin_header.title_placeholder')}}" value="{{ $content->title }}">
              </div>
              <div class="mb-4">
                <label class="form-label p-0" for="description">{{__('admin_header.description')}}</label>
                <textarea class="form-control" id="description" name="description" rows="8" placeholder="{{__('admin_header.description_placeholder')}}">{{ $content->description }}</textarea>
              </div>
              <div class="mb-4">
                <label class="form-label p-0" for="facebook">{{__('admin_header.facebook')}}</label>
                <input type="text" class="form-control" id="facebook" name="facebook" placeholder="{{__('admin_header.facebook_placeholder')}}" value="{{ $content->facebook }}">
              </div>
              <div class="mb-4">
                <label class="form-label p-0" for="instagram">{{__('admin_header.instagram')}}</label>
                <input type="text" class="form-control" id="instagram" name="instagram" placeholder="{{__('admin_header.instagram_placeholder')}}" value="{{ $content->instagram }}">
              </div>
              <div class="mb-4">
                <label class="form-label p-0" for="twitter">{{__('admin_header.twitter')}}</label>
                <input type="text" class="form-control" id="twitter" name="twitter" placeholder="{{__('admin_header.twitter_placeholder')}}" value="{{ $content->twitter }}">
              </div>
              <div class="mb-4">
                <label class="form-label p-0" for="youtube">{{__('admin_header.youtube')}}</label>
                <input type="text" class="form-control" id="youtube" name="youtube" placeholder="{{__('admin_header.youtube_placeholder')}}" value="{{ $content->youtube }}">
              </div>
              <div class="mb-4">
                <label class="form-label p-0" for="linkedin">{{__('admin_header.linkedin')}}</label>
                <input type="text" class="form-control" id="linkedin" name="linkedin" placeholder="{{__('admin_header.linkedin_placeholder')}}" value="{{ $content->linkedin }}">
              </div>
              <div class="mb-4">
                <label class="form-label p-0" for="pinterest">{{__('admin_header.pinterest')}}</label>
                <input type="text" class="form-control" id="pinterest" name="pinterest" placeholder="{{__('admin_header.pinterest_placeholder')}}" value="{{ $content->pinterest }}">
              </div>
              <div class="mb-4">
                <label class="form-label p-0" for="google">{{__('admin_header.google')}}</label>
                <input type="text" class="form-control" id="google" name="google" placeholder="{{__('admin_header.google_placeholder')}}" value="{{ $content->google }}">
              </div>
              <div class="mb-4">
                <div class="row">
                  <div class="col-md-6 col-lg-8 col-xl-8 col-xxl-9"></div>
                  <div class="col-md-6 col-lg-4 col-xl-4 col-xxl-3">
                    <div class="input-group" >
                      <input style="width:100%;" type="submit" class="btn btn btn-primary my-0 mx-1" id="updateheadersettings" name="updateheadersettings" value="{{__('admin_header.update')}}">
                    </div>
                  </div>
                </div>
              </div>
            </form>
          </div>
          <div class="tab-pane fade @if (session()->get('tab_page') == 'image_large') show active @endif" id="top-image-large" role="tabpanel" aria-labelledby="profile-top-tab">
            <form action="{{ route('admin.header-settings.update',[$content->id]) }}" method="POST" enctype="multipart/form-data">
              @csrf
              @method('PUT')
              <input type="hidden" name="page" value="{{ request('page') }}">
              <!-- Image Cropper -->
              <div class="row items-push d-flex align-items-stretch">
                <div class="col-xxl-6">
                  <h4 class="border-bottom pb-2">{{__('admin_header.large_screen_image')}}</h4>
                  <!--
                    RATIO & WIDTH Settings.
                  -->
                  <input type="hidden" class="img-w" value="{{ $large_size->width }}">
                  <input type="hidden" class="img-h" value="{{ $large_size->height }}">
                  <input type="hidden" class="ratio" value="{{ $large_size->ratio }}">
                  <div class="original text-center">
                    @if ($content->logo_large_screen)
                    <img id="js-img-cropper" class="img-fluid" src="{{ asset($content->logo_large_screen) }}" alt="photo">
                    @else
                    <img id="js-img-cropper" class="img-fluid" src="{{ asset('uploads/media/constant/placeholder.png') }}" alt="photo">
                    @endif
                  </div>
                  <div class="text-center">
                    <p class="my-2" class="fw-ligth">{{ $large_size->width }}x{{ $large_size->height }} px</p>
                  </div>
                  <div id="box" class="block-content text-center d-none cropper-buttons">
                    <div class="row">
                      <button type="button" class="btn btn-primary crop" data-toggle="cropper"
                      data-method="crop" title="{{__('admin_header.crop_popup')}}">
                      {{__('admin_header.crop')}}
                      </button>
                    </div>
                  </div>
                  <div class="my-4">
                    <input type="file" class="form-control" id="image" name="logo_large_screen" accept="image/*">
                    <small class="fw-light text-gray-dark">{{__('admin_header.file_mimes')}}</small>
                    <input type="hidden" name="cropped_data" id="cropped_data"> <!--base64 data transfer-->
                  </div>
                </div>

                <div id="box-2" class="col-xxl-6 d-none">
                  <h4 class="border-bottom pb-2">{{__('admin_header.preview')}}</h4>
                  <!--rightbox-->
                  <div class="img-result text-center">
                    <!-- Tesult of the crop -->
                    <img class="cropped img-fluid cropped-preview" src="" alt="">
                  </div>
                  <div class="row">
                    <input type="submit" class="btn btn-lg btn-primary updateimagebutton" id="updateimagelarge" name="updateimagelarge" value="{{__('admin_header.upload_image')}}">
                  </div>
                </div>
              </div>
              <!-- END Image Cropper -->
            </form>
          </div>
          <div class="tab-pane fade @if (session()->get('tab_page') == 'image_small') show active @endif" id="top-image-small" role="tabpanel" aria-labelledby="contact-top-tab">
            <form action="{{ route('admin.header-settings.update',[$content->id]) }}" method="POST" enctype="multipart/form-data">
              @csrf
              @method('PUT')
              <input type="hidden" name="page" value="{{ request('page') }}">
              <!-- Image Cropper -->
              <div class="row items-push d-flex align-items-stretch">
                <div class="col-xxl-6">
                  <h4 class="border-bottom pb-2">{{__('admin_header.small_screen_image')}}</h4>
                  <!--
                    RATIO & WIDTH Settings.
                  -->
                    <input type="hidden" class="img-w-mobile" value="{{ $mobile_size->width }}">
                    <input type="hidden" class="img-h-mobile" value="{{ $mobile_size->height }}">
                    <input type="hidden" class="ratio-mobile" value="{{ $mobile_size->ratio }}">
                  <div class="original-mobile text-center">
                    @if ($content->logo_small_screen)
                    <img id="js-img-cropper" class="img-fluid" src="{{ asset($content->logo_small_screen) }}" alt="photo">
                    @else
                    <img id="js-img-cropper" class="img-fluid" src="{{ asset('uploads/media/constant/placeholder.png') }}" alt="photo">
                    @endif
                  </div>
                  <div class="text-center">
                    <p class="my-2" class="fw-ligth">{{ $mobile_size->width }}x{{ $mobile_size->height }} px</p>
                  </div>
                  <div id="box-mobile" class="block-content text-center d-none cropper-buttons">
                    <div class="row">
                      <button type="button" class="btn btn-primary crop-mobile" data-toggle="cropper-mobile"
                      data-method="crop" title="{{__('admin_header.crop_popup')}}">
                      {{__('admin_header.crop')}}
                      </button>
                    </div>
                  </div>
                  <div class="my-4">
                    <input type="file" class="form-control" id="image-mobile" name="logo_small_screen" accept="image/*">
                    <small class="fw-light text-gray-dark">{{__('admin_header.file_mimes')}}</small>
                    <input type="hidden" name="cropped_data_mobile" id="cropped_data_mobile"> <!--base64 data transferi-->
                  </div>
                </div>

                <div id="box-2-mobile" class="col-xxl-6 d-none">
                  <h4 class="border-bottom pb-2">{{__('admin_header.preview')}}</h4>
                  <!--rightbox-->
                  <div class="img-result-mobile text-center">
                    <!-- Result of the crop -->
                    <img class="cropped-mobile img-fluid cropped-preview" src="" alt="">
                  </div>
                  <div class="row">
                    <input type="submit" class="btn btn-lg btn-primary updateimagebutton" id="updateimagesmall" name="updateimagesmall" value="{{__('admin_header.upload_image')}}">
                  </div>
                </div>
              </div>
              <!-- END Image Cropper -->
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Container-fluid Ends-->
</div>
<!-- END Main Container -->

@endsection
