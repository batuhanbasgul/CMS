@extends('admin.layouts.base')

@section('title',__('admin_slider.update_content'))

@section('content')
<!-- Main Container -->
<div class="page-body pt-5">
  <div class="container-fluid">
    <div class="page-header">
      <div class="row">
        <div class="col-7">
            <div class="row">
                <div class="col-7">
                    <h3>{{__('admin_slider.update_content')}} @if(count($langs) != 1)<span class="title-lang-code">({{ request('lang_code') }})</span>@endif</h3>
                    <ol class="breadcrumb">
                      <li class="breadcrumb-item disappear-500"><a href="{{ route('admin.index') }}"><ins> {{__('admin_slider.main_page')}}</ins></a></li>
                      <li class="breadcrumb-item disappear-500">{{__('admin_slider.slider')}}</li>
                      <li class="breadcrumb-item disappear-500">{{ $slider->title }}</li>
                    </ol>
                </div>
                <div class="col-5">
                    <!--
                      Refreshing page with selected language code.
                    -->
                    @if(count($langs) != 1)
                      @foreach ($langs as $lang)
                        @if (request('lang_code')==$lang->lang_code)
                        <div class="mx-2 d-none">
                          <span class="disabled">({{__('admin_slider.current_language')}})</span>
                          <img src="{{ asset($lang->icon) }}" alt="">
                        </div>
                        @endif
                      @endforeach
                      <select class="form-select" id="lang-select" name="lang-select" onchange="window.location.href=this.options[this.selectedIndex].value;">
                          @foreach ($langs as $lang)
                          @if ($lang->is_active)
                          <option value="{{ route('admin.slider-settings.index',['lang_code'=>$lang->lang_code, 'page'=>request('page')]) }}"
                            @if (request('lang_code')==$lang->lang_code)
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
          <a class="mx-5" href="{{ route('admin.slider-settings.index',['lang_code' => request('lang_code')]) }}">
            <button type="button" class="btn btn-primary btn-sm m-1">
                <i class="me-2 fa-solid fa-angles-left"></i><span>{{__('admin_slider.turn_back')}}</span>
            </button>
          </a>
        </div>
      </div>
    </div>
  </div>
  <!-- Sweet Alert -->
  @if(session('success'))
  <script>swal("{{__('admin_slider.success')}}", "{{__('admin_slider.update_success_context')}}", "success");</script>
  @elseif(session('error'))
  <script>swal("{{__('admin_slider.error')}}", "{{__('admin_slider.update_error_context')}}", "error");</script>
  @elseif(session('file_extension_error'))
  <script>swal("{{__('admin_slider.error')}}", "{{__('admin_slider.update_error_file_mime')}}", "error");</script>
  @elseif(session('file_size_error'))
  <script>swal("{{__('admin_slider.error')}}", "{{__('admin_slider.update_error_file_size')}}", "error");</script>
  @endif
  <!-- Container-fluid starts-->
  <div class="container-fluid">
    <div class="card">
      <div class="card-body">
        <ul class="nav nav-tabs border-tab" id="top-tab" role="tablist">
          <li class="nav-item"><a class="nav-link @if (!session('tab_page') || session()->get('tab_page') == 'context')) active @endif" data-bs-toggle="tab"
              href="#top-content" role="tab" aria-controls="top-content" aria-selected="true">
              <i class="fas fa-book-open"></i>{{__('admin_slider.content')}}</a></li>
          <li class="nav-item"><a class="nav-link @if (session()->get('tab_page') == 'image_large') active @endif" data-bs-toggle="tab"
              href="#top-image-large" role="tab" aria-controls="top-image-large" aria-selected="false">
              <i class="fas fa-desktop"></i>{{__('admin_slider.big_image')}}</a></li>
          <li class="nav-item"><a class="nav-link @if (session()->get('tab_page') == 'image_small') active @endif" data-bs-toggle="tab"
              href="#top-image-small" role="tab" aria-controls="top-image-small" aria-selected="false">
              <i class="fas fa-mobile-alt"></i>{{__('admin_slider.small_image')}}</a></li>
        </ul>
        <div class="tab-content" id="top-tabContent">
          <div class="tab-pane fade @if (!session()->get('tab_page') || session()->get('tab_page') == 'context')) show active @endif" id="top-content" role="tabpanel" aria-labelledby="top-content-tab">
            <form class="theme-form needs-validation" novalidate="" action="{{ route('admin.slider-settings.update',[$slider->id]) }}" method="POST">
              @csrf
              @method('PUT')
              <input type="hidden" name="page" value="{{ request('page') }}">
              <h4 class="border-bottom pb-2">{{__('admin_slider.slider_content')}}</h4>
              <div class="mb-3">
                <label class="col-form-label p-0" for="title">{{__('admin_slider.title')}}</label><span class="text-danger">*</span>
                <input class="form-control" id="title" name="title" type="text" placeholder="{{__('admin_slider.title_placeholder')}}" value="{{ $slider->title }}" required="">
                <div class="valid-feedback">{{__('admin_slider.looks_good')}}</div>
              </div>
              <div class="mb-3">
                <label class="col-form-label p-0" for="link">{{__('admin_slider.target_url')}}</label>
                <input class="form-control" id="link" name="link" type="text" placeholder="{{__('admin_slider.target_url_placeholder')}}" value="{{ $slider->link }}">
              </div>
              <div class="mb-3">
                <label class="col-form-label p-0" for="video_embed_code">{{__('admin_slider.youtube_code')}}</label>
                <input class="form-control" id="video_embed_code" name="video_embed_code" type="text" placeholder="{{__('admin_slider.youtube_code_placeholder')}}" value="{{ $slider->video_embed_code }}">
              </div>
              <div class="mb-3">
                <label class="col-form-label p-0" for="logo">{{__('admin_slider.logo')}}</label>
                <input class="form-control" id="logo" name="logo" type="text" placeholder="{{__('admin_slider.logo_placeholder')}}" value="{{ $slider->logo }}">
              </div>
              <div class="mb-3">
                <label class="col-form-label p-0" for="logo_title">{{__('admin_slider.logo_title')}}</label>
                <input class="form-control" id="logo_title" name="logo_title" type="text" placeholder="{{__('admin_slider.logo_title_placeholder')}}" value="{{ $slider->logo_title }}">
              </div>
              <div class="mb-4">
                <label class="form-label p-0" for="description">{{__('admin_slider.description')}}</label>
                <textarea class="form-control" id="description" name="description" rows="8" placeholder="{{__('admin_slider.description_placeholder')}}">{{ $slider->description }}</textarea>
              </div>
              <div>
                <div class="form-check form-switch my-0">
                  <input class="form-check-input custom-switch" type="checkbox" name="is_desktop_active" id="is_desktop_active" @if ($slider->is_desktop_active) checked @endif>
                  <label class="form-check-label custom-switch-label" for="is_desktop_active">{{__('admin_slider.large_screen_active')}}</label>
                </div>
              </div>
              <div>
                <div class="form-check form-switch my-0">
                  <input class="form-check-input custom-switch" type="checkbox" name="is_mobile_active" id="is_mobile_active" @if ($slider->is_mobile_active) checked @endif>
                  <label class="form-check-label custom-switch-label" for="is_mobile_active">{{__('admin_slider.small_screen_active')}}</label>
                </div>
              </div>
              <div class="mb-4">
                <div class="row">
                  <div class="col-md-6 col-lg-8 col-xl-8 col-xxl-9"></div>
                  <div class="col-md-6 col-lg-4 col-xl-4 col-xxl-3">
                    <div class="input-group" >
                      <input style="width:100%;" type="submit" class="btn btn btn-primary my-0 mx-1" id="updateslidersettings" name="updateslidersettings" value="{{__('admin_slider.update')}}">
                    </div>
                  </div>
                </div>
              </div>
            </form>
          </div>
          <div class="tab-pane fade @if (session()->get('tab_page') == 'image_large') show active @endif" id="top-image-large" role="tabpanel" aria-labelledby="profile-top-tab">
            <form action="{{ route('admin.slider-settings.update',[$slider->id]) }}" method="POST" enctype="multipart/form-data">
              @csrf
              @method('PUT')
              <input type="hidden" name="page" value="{{ request('page') }}">
              <!-- Image Cropper -->
              <div class="row items-push d-flex align-items-stretch">
                <div class="col-xxl-6">
                  <h4 class="border-bottom pb-2">{{__('admin_slider.large_screen_image')}}</h4>
                  <!--
                    RATIO & WIDTH Settings.
                  -->
                  <input type="hidden" class="img-w" value="{{ $large_size->width }}">
                  <input type="hidden" class="img-h" value="{{ $large_size->height }}">
                  <input type="hidden" class="ratio" value="{{ $large_size->ratio }}">
                  <div class="original text-center">
                    @if ($slider->image_large_screen)
                    <img id="js-img-cropper" class="img-fluid" src="{{ asset($slider->image_large_screen) }}" alt="photo">
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
                      data-method="crop" title="{{__('admin_slider.crop_popup')}}">
                      {{__('admin_slider.crop')}}
                      </button>
                    </div>
                  </div>
                  <div class="my-4">
                    <input type="file" class="form-control" id="image" name="image_large_screen" accept="image/*">
                    <small class="fw-light text-gray-dark">{{__('admin_slider.file_mimes')}}</small>
                    <input type="hidden" name="cropped_data" id="cropped_data"> <!--base64 data transferi-->
                  </div>
                </div>

                <div id="box-2" class="col-xxl-6 d-none">
                  <h4 class="border-bottom pb-2">{{__('admin_slider.preview')}}</h4>
                  <!--rightbox-->
                  <div class="img-result text-center">
                    <!-- Result of crop -->
                    <img class="cropped img-fluid cropped-preview" src="" alt="">
                  </div>
                  <div class="row">
                    <input type="submit" class="btn btn-lg btn-primary updateimagebutton" id="updateimagelarge" name="updateimagelarge" value="{{__('admin_slider.upload_image')}}">
                  </div>
                </div>
              </div>
              <!-- END Image Cropper -->
            </form>
          </div>
          <div class="tab-pane fade @if (session()->get('tab_page') == 'image_small') show active @endif" id="top-image-small" role="tabpanel" aria-labelledby="contact-top-tab">
            <form action="{{ route('admin.slider-settings.update',[$slider->id]) }}" method="POST" enctype="multipart/form-data">
              @csrf
              @method('PUT')
              <input type="hidden" name="page" value="{{ request('page') }}">
              <!-- Image Cropper -->
              <div class="row items-push d-flex align-items-stretch">
                <div class="col-xxl-6">
                  <h4 class="border-bottom pb-2">{{__('admin_slider.small_screen_image')}}</h4>
                  <!--
                    RATIO & WIDTH Settings.
                  -->
                    <input type="hidden" class="img-w-mobile" value="{{ $mobile_size->width }}">
                    <input type="hidden" class="img-h-mobile" value="{{ $mobile_size->height }}">
                    <input type="hidden" class="ratio-mobile" value="{{ $mobile_size->ratio }}">
                  <div class="original-mobile text-center">
                    @if ($slider->image_small_screen)
                    <img id="js-img-cropper" class="img-fluid" src="{{ asset($slider->image_small_screen) }}" alt="photo">
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
                      data-method="crop" title="{{__('admin_slider.crop_popup')}}">
                      {{__('admin_slider.crop')}}
                      </button>
                    </div>
                  </div>
                  <div class="my-4">
                    <input type="file" class="form-control" id="image-mobile" name="image_small_screen" accept="image/*">
                    <small class="fw-light text-gray-dark">{{__('admin_slider.file_mimes')}}</small>
                    <input type="hidden" name="cropped_data_mobile" id="cropped_data_mobile"> <!--base64 data transferi-->
                  </div>
                </div>

                <div id="box-2-mobile" class="col-xxl-6 d-none">
                  <h4 class="border-bottom pb-2">{{__('admin_slider.preview')}}</h4>
                  <!--rightbox-->
                  <div class="img-result-mobile text-center">
                    <!-- Result of crop -->
                    <img class="cropped-mobile img-fluid cropped-preview" src="" alt="">
                  </div>
                  <div class="row">
                    <input type="submit" class="btn btn-lg btn-primary updateimagebutton" id="updateimagesmall" name="updateimagesmall" value="{{__('admin_slider.upload_image')}}">
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
