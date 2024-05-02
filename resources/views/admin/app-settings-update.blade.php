@extends('admin.layouts.base')

@section('title',__('admin_app_settings.update_content'))

@section('content')
<!-- Main Container -->
<div class="page-body pt-5">
  <div class="container-fluid">
    <div class="page-header">
      <div class="row">
        <div class="col-7">
            <div class="row">
                <div class="col-7">
                    <h3>{{__('admin_app_settings.update_content')}} @if(count($langs) != 1)<span class="title-lang-code">({{ request('lang_code') }})</span>@endif</h3>
                    <ol class="breadcrumb">
                      <li class="breadcrumb-item disappear-500"><a href="{{ route('admin.index') }}"><ins> {{__('admin_app_settings.main_page')}}</ins></a></li>
                      <li class="breadcrumb-item disappear-500">{{__('admin_app_settings.main_page_settings')}}</li>
                      <li class="breadcrumb-item disappear-500">{{__('admin_app_settings.update_content')}}</li>
                    </ol>
                </div>
                <div class="col-5">
                    <!--
                      Refreshing the page with selected lang code.
                    -->
                    @if(count($langs) != 1)
                      @foreach ($langs as $lang)
                        @if ($lang_code==$lang->lang_code)
                        <div class="mx-2 d-none">
                          <span class="disabled">({{__('admin_app_settings.current_language')}})</span>
                          <img src="{{ asset($lang->icon) }}" alt="">
                        </div>
                        @endif
                      @endforeach
                      <select class="form-select" id="lang-select" name="lang-select" onchange="window.location.href=this.options[this.selectedIndex].value;">
                          @foreach ($langs as $lang)
                          @if ($lang->is_active)
                          <option value="{{ route('admin.app-settings.index',['lang_code'=>$lang->lang_code, 'page'=>request('page')]) }}"
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
            <div class="col-5 d-flex justify-content-end">
              <a class="mx-5" href="{{ route('admin.app-settings.index',['lang_code' => request('lang_code')]) }}">
                <button type="button" class="btn btn-primary btn-sm m-1">
                    <i class="me-2 fa-solid fa-angles-left"></i><span> {{__('admin_app_settings.turn_back')}}</span>
                </button>
              </a>
            </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Sweet Alert -->
  @if(session('success'))
  <script>swal("{{__('admin_app_settings.success')}}", "{{__('admin_app_settings.update_success_context')}}", "success");</script>
  @elseif(session('error'))
  <script>swal("{{__('admin_app_settings.error')}}", "{{__('admin_app_settings.update_error_context')}}", "error");</script>
  @elseif(session('file_extension_error'))
  <script>swal("{{__('admin_app_settings.error')}}", "{{__('admin_app_settings.update_error_file_mime')}}", "error");</script>
  @elseif(session('file_size_error'))
  <script>swal("{{__('admin_app_settings.error')}}", "{{__('admin_app_settings.update_error_file_size')}}", "error");</script>
  @endif
  <!-- Container-fluid starts-->
  <div class="container-fluid">
    <div class="card">
      <div class="card-body">
        <ul class="nav nav-tabs border-tab" id="top-tab" role="tablist">
          <li class="nav-item"><a class="nav-link @if (!session('tab_page') || session()->get('tab_page') == 'context')) active @endif" data-bs-toggle="tab"
              href="#top-content" role="tab" aria-controls="top-content" aria-selected="true">
              <i class="fas fa-book-open"></i>{{__('admin_app_settings.context')}}</a></li>
          <li class="nav-item"><a class="nav-link @if (session()->get('tab_page') == 'fav_icon') active @endif" data-bs-toggle="tab"
              href="#top-fav-icon" role="tab" aria-controls="top-fav-icon" aria-selected="false">
              <i class="fa-solid fa-image"></i>{{__('admin_app_settings.icon')}}</a></li>
          <li class="nav-item"><a class="nav-link @if (session()->get('tab_page') == 'image_banner') active @endif" data-bs-toggle="tab"
              href="#top-image-banner" role="tab" aria-controls="top-image-banner" aria-selected="false">
              <i class="fa-solid fa-panorama"></i>{{__('admin_app_settings.banner')}}</a></li>
        </ul>
        <div class="tab-content" id="top-tabContent">
          <div class="tab-pane fade @if (!session()->get('tab_page') || session()->get('tab_page') == 'context')) show active @endif" id="top-content" role="tabpanel" aria-labelledby="top-content-tab">
            <form class="theme-form needs-validation" novalidate="" action="{{ route('admin.app-settings.update',[$settings->id]) }}" method="POST">
              @csrf
              @method('PUT')
              <input type="hidden" name="page" value="{{ request('page') }}">
              <h4 class="border-bottom pb-2">{{__('admin_app_settings.update_content')}}</h4>
              <div class="mb-3">
                <label class="col-form-label p-0" for="title">{{__('admin_app_settings.title')}}</label><span class="text-danger">*</span>
                <input class="form-control" id="title" name="title" type="text" placeholder="{{__('admin_app_settings.title_placeholder')}}" value="{{ $settings->title }}" required="">
                <div class="valid-feedback">{{__('admin_app_settings.looks_good')}}</div>
              </div>
              <div class="mb-3">
                <label class="col-form-label p-0" for="app_description">{{__('admin_app_settings.description')}}</label>
                <textarea class="form-control" id="app_description" name="app_description" rows="12" placeholder="{{__('admin_app_settings.description_placeholder')}}">{{ $settings->description }}</textarea>
              </div>
              <div class="mb-3">
                <label class="col-form-label p-0" for="keywords">{{__('admin_app_settings.keywords')}}</label>
                <textarea class="form-control" id="keywords" name="keywords" rows="6" placeholder="{{__('admin_app_settings.keywords_placeholder')}}">{{ $settings->keywords }}</textarea>
              </div>
              <div class="mb-3">
                <label class="col-form-label p-0" for="google_analytic">{{__('admin_app_settings.google_analytics')}}</label>
                <textarea class="form-control" id="google_analytic" name="google_analytic" rows="6" placeholder="{{__('admin_app_settings.google_analytics_placeholder')}}">{{ $settings->google_analytic }}</textarea>
              </div>
              <div class="mb-3">
                <label class="col-form-label p-0" for="yandex_verification_code">{{__('admin_app_settings.yandex_verification')}}</label>
                <textarea class="form-control" id="yandex_verification_code" name="yandex_verification_code" rows="6" placeholder="{{__('admin_app_settings.yandex_verification_placeholder')}}">{{ $settings->yandex_verification_code }}</textarea>
              </div>
              <div class="mb-4">
                <div class="row">
                  <div class="col-md-6 col-lg-8 col-xl-8 col-xxl-9"></div>
                  <div class="col-md-6 col-lg-4 col-xl-4 col-xxl-3">
                    <div class="input-group" >
                      <input style="width:100%;" type="submit" class="btn btn btn-primary my-0 mx-1" id="updateappsettings" name="updateappsettings" value="{{__('admin_app_settings.update')}}">
                    </div>
                  </div>
                </div>
              </div>
            </form>
          </div>
          <div class="tab-pane fade @if (session()->get('tab_page') == 'fav_icon') show active @endif" id="top-fav-icon" role="tabpanel" aria-labelledby="profile-top-tab">
            <form action="{{ route('admin.app-settings.update',[$settings->id]) }}" method="POST" enctype="multipart/form-data">
              @csrf
              @method('PUT')
              <input type="hidden" name="page" value="{{ request('page') }}">
              <!-- Image Cropper -->
              <div class="row items-push d-flex align-items-stretch">
                <div class="col-xxl-6">
                  <h4 class="border-bottom pb-2">{{__('admin_app_settings.icon')}}</h4>
                  <!--
                    RATIO & WIDTH Ayarları.
                  -->
                  <input type="hidden" class="img-w-favicon" value="{{ $favicon_size->width }}">
                  <input type="hidden" class="img-h-favicon" value="{{ $favicon_size->height }}">
                  <input type="hidden" class="ratio-favicon" value="{{ $favicon_size->ratio }}">
                  <div class="original-favicon text-center">
                    @if ($settings->fav_icon)
                    <img id="js-img-cropper" class="img-fluid" src="{{ asset($settings->fav_icon) }}" alt="photo">
                    @else
                    <img id="js-img-cropper" class="img-fluid" src="{{ asset('uploads/media/constant/placeholder.png') }}" alt="photo">
                    @endif
                  </div>
                  <div class="text-center">
                    <p class="my-2" class="fw-ligth">{{ $favicon_size->width }}x{{ $favicon_size->height }} px</p>
                  </div>
                  <div id="box-favicon" class="block-content text-center d-none cropper-buttons">
                    <div class="row">
                      <button type="button" class="btn btn-primary crop-favicon" data-toggle="cropper-favicon"
                      data-method="crop" title="{{__('admin_app_settings.crop_popup')}}">
                      {{__('admin_app_settings.crop')}}
                      </button>
                    </div>
                  </div>
                  <div class="my-4">
                    <input type="file" class="form-control" id="image-favicon" name="fav_icon" accept="image/*">
                    <small class="fw-light text-gray-dark">{{__('admin_app_settings.file_mimes')}}</small>
                    <input type="hidden" name="cropped_data_favicon" id="cropped_data_favicon"> <!--base64 data transferi-->
                  </div>
                </div>

                <div id="box-2-favicon" class="col-xxl-6 d-none">
                  <h4 class="border-bottom pb-2">{{__('admin_app_settings.preview')}}</h4>
                  <!--rightbox-->
                  <div class="img-result-favicon text-center">
                    <!-- result of crop -->
                    <img class="cropped-favicon img-fluid cropped-preview-favicon" src="" alt="">
                  </div>
                  <div class="row">
                    <input type="submit" class="btn btn-lg btn-primary updateimagebutton" id="updateimagefavicon" name="updateimagefavicon" value="{{__('admin_app_settings.upload_image')}}">
                  </div>
                </div>
              </div>
              <!-- END Image Cropper -->
            </form>
          </div>
          <div class="tab-pane fade @if (session()->get('tab_page') == 'image_banner') show active @endif" id="top-image-banner" role="tabpanel" aria-labelledby="contact-top-tab">
            <form action="{{ route('admin.app-settings.update',[$settings->id]) }}" method="POST" enctype="multipart/form-data">
              @csrf
              @method('PUT')
              <input type="hidden" name="page" value="{{ request('page') }}">
              <!-- Image Cropper -->
              <div class="row items-push d-flex align-items-stretch">
                <div class="col-xxl-6">
                  <h4 class="border-bottom pb-2">{{__('admin_announcement.banner_image')}}</h4>
                  <!--
                    RATIO & WIDTH Ayarları.
                  -->
                    <input type="hidden" class="img-w-banner" value="{{ $banner_size->width }}">
                    <input type="hidden" class="img-h-banner" value="{{ $banner_size->height }}">
                    <input type="hidden" class="ratio-banner" value="{{ $banner_size->ratio }}">
                  <div class="original-banner text-center">
                    @if ($settings->hero)
                    <img id="js-img-cropper" class="img-fluid" src="{{ asset($settings->hero) }}" alt="photo">
                    @else
                    <img id="js-img-cropper" class="img-fluid" src="{{ asset('uploads/media/constant/placeholder.png') }}" alt="photo">
                    @endif
                  </div>
                  <div class="text-center">
                    <p class="my-2" class="fw-ligth">{{ $banner_size->width }}x{{ $banner_size->height }} px</p>
                  </div>
                  <div id="box-banner" class="block-content text-center d-none cropper-buttons">
                    <div class="row">
                      <button type="button" class="btn btn-primary crop-banner" data-toggle="cropper-banner"
                      data-method="crop" title="{{__('admin_app_settings.crop_popup')}}">
                      {{__('admin_app_settings.crop')}}
                      </button>
                    </div>
                  </div>
                  <div class="my-4">
                    <input type="file" class="form-control" id="image-banner" name="hero" accept="image/*">
                    <small class="fw-light text-gray-dark">{{__('admin_app_settings.file_mimes')}}</small>
                    <input type="hidden" name="cropped_data_banner" id="cropped_data_banner"> <!--base64 data transferi-->
                  </div>
                </div>

                <div id="box-2-banner" class="col-xxl-6 d-none">
                  <h4 class="border-bottom pb-2">{{__('admin_app_settings.preview')}}</h4>
                  <!--rightbox-->
                  <div class="img-result-banner text-center">
                    <!-- result of crop -->
                    <img class="cropped-banner img-fluid cropped-preview" src="" alt="">
                  </div>
                  <div class="row">
                    <input type="submit" class="btn btn-lg btn-primary updateimagebutton" id="updateimagebanner" name="updateimagebanner" value="{{__('admin_app_settings.upload_image')}}">
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
