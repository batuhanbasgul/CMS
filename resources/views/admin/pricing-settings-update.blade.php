@extends('admin.layouts.base')

@section('title',__('admin_pricing.update_content'))

@section('content')
<!-- Main Container -->
<div class="page-body pt-5">
  <div class="container-fluid">
    <div class="page-header">
      <div class="row">
        <div class="col-7">
            <div class="row">
                <div class="col-7">
                    <h3>{{__('admin_pricing.update_content')}} @if(count($langs) != 1)<span class="title-lang-code">({{ request('lang_code') }})</span>@endif</h3>
                    <ol class="breadcrumb">
                      <li class="breadcrumb-item disappear-500"><a href="{{ route('admin.index') }}"><ins> {{__('admin_pricing.main_page')}}</ins></a></li>
                      <li class="breadcrumb-item disappear-500">{{__('admin_pricing.price')}}</li>
                      <li class="breadcrumb-item disappear-500">{{ $price->title }}</li>
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
                          <span class="disabled">({{__('admin_pricing.current_language')}})</span>
                          <img src="{{ asset($lang->icon) }}" alt="">
                        </div>
                        @endif
                      @endforeach
                      <select class="form-select" id="lang-select" name="lang-select" onchange="window.location.href=this.options[this.selectedIndex].value;">
                          @foreach ($langs as $lang)
                          @if ($lang->is_active)
                          <option value="{{ route('admin.pricing-settings.index',['lang_code'=>$lang->lang_code, 'page'=>request('page')]) }}"
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
          <a class="mx-5" href="{{ route('admin.pricing-settings.index',['lang_code' => request('lang_code')]) }}">
            <button type="button" class="btn btn-primary btn-sm m-1">
                <i class="me-2 fa-solid fa-angles-left"></i><span> {{__('admin_pricing.turn_back')}}</span>
            </button>
          </a>
        </div>
      </div>
    </div>
  </div>
  <!-- Sweet Alert -->
    @if(session('success'))
    <script>swal("{{__('admin_pricing.success')}}", "{{__('admin_pricing.update_success_context')}}", "success");</script>
    @elseif(session('error'))
    <script>swal("{{__('admin_pricing.error')}}", "{{__('admin_pricing.update_error_context')}}", "error");</script>
    @elseif(session('file_extension_error'))
    <script>swal("{{__('admin_pricing.error')}}", "{{__('admin_pricing.update_error_file_mime')}}", "error");</script>
    @elseif(session('file_size_error'))
    <script>swal("{{__('admin_pricing.error')}}", "{{__('admin_pricing.update_error_file_size')}}", "error");</script>
    @endif
  <!-- Container-fluid starts-->
  <div class="container-fluid">
    <div class="card">
      <div class="card-body">
        <ul class="nav nav-tabs border-tab" id="top-tab" role="tablist">
          <li class="nav-item"><a class="nav-link @if (!session('tab_page') || session()->get('tab_page') == 'context')) active @endif" data-bs-toggle="tab"
              href="#top-content" role="tab" aria-controls="top-content" aria-selected="true">
              <i class="fas fa-book-open"></i>{{__('admin_pricing.content')}}</a></li>
          <li class="nav-item"><a class="nav-link @if (session()->get('tab_page') == 'image_large') active @endif" data-bs-toggle="tab"
              href="#top-image-large" role="tab" aria-controls="top-image-large" aria-selected="false">
              <i class="fas fa-desktop"></i>{{__('admin_pricing.big_image')}}</a></li>
          <li class="nav-item"><a class="nav-link @if (session()->get('tab_page') == 'image_small') active @endif" data-bs-toggle="tab"
              href="#top-image-small" role="tab" aria-controls="top-image-small" aria-selected="false">
              <i class="fas fa-mobile-alt"></i>{{__('admin_pricing.small_image')}}</a></li>
        </ul>
        <div class="tab-content" id="top-tabContent">
          <div class="tab-pane fade @if (!session()->get('tab_page') || session()->get('tab_page') == 'context')) show active @endif" id="top-content" role="tabpanel" aria-labelledby="top-content-tab">
            <form class="theme-form needs-validation" novalidate="" action="{{ route('admin.pricing-settings.update',[$price->id]) }}" method="POST">
              @csrf
              @method('PUT')
              <input type="hidden" name="page" value="{{ request('page') }}">
              <h4 class="border-bottom pb-2">{{__('admin_pricing.price_content')}}</h4>
              <div class="mb-3">
                <label class="col-form-label p-0" for="title">{{__('admin_pricing.title')}}</label><span class="text-danger">*</span>
                <input class="form-control" id="title" name="title" type="text" placeholder="{{__('admin_pricing.title_placeholder')}}" value="{{ $price->title }}" required="">
                <div class="valid-feedback">{{__('admin_pricing.looks_good')}}</div>
              </div>
              <div class="mb-3">
                <label class="col-form-label p-0" for="subtitle">{{__('admin_pricing.subtitle')}}</label>
                <input class="form-control" id="subtitle" name="subtitle" type="text" placeholder="{{__('admin_pricing.subtitle_placeholder')}}" value="{{ $price->subtitle }}">
              </div>
              <div class="mb-3">
                <label class="col-form-label p-0" for="pricing_url">{{__('admin_pricing.price_url')}}</label>
                <input class="form-control" id="pricing_url" name="pricing_url" type="text" placeholder="{{__('admin_pricing.price_url_placeholder')}}" value="{{ $price->pricing_url }}">
              </div>
              <div class="mb-3">
                <label class="col-form-label p-0" for="video_embed_code">{{__('admin_pricing.youtube_code')}}</label>
                <input class="form-control" id="video_embed_code" name="video_embed_code" type="text" placeholder="{{__('admin_pricing.youtube_code_placeholder')}}" value="{{ $price->video_embed_code }}">
              </div>
              <div class="mb-4">
                <label class="form-label p-0" for="keywords">{{__('admin_pricing.keywords')}}</label>
                <textarea class="form-control" id="keywords" name="keywords" rows="4" placeholder="{{__('admin_pricing.keywords_placeholder')}}">{{ $price->keywords }}</textarea>
              </div>
              <div class="mb-4">
                <label class="form-label p-0" for="description">{{__('admin_pricing.description')}}</label>
                <textarea class="form-control" id="description" name="description" rows="8" placeholder="{{__('admin_pricing.description_placeholder')}}">{{ $price->description }}</textarea>
              </div>
              <div class="mb-3">
                <label class="col-form-label p-0" for="entry1">{{__('admin_pricing.entry1')}}</label>
                <input class="form-control" id="entry1" name="entry1" type="text" placeholder="{{__('admin_pricing.entry1_placeholder')}}" value="{{ $price->entry1 }}">
              </div>
              <div class="mb-3">
                <label class="col-form-label p-0" for="entry2">{{__('admin_pricing.entry2')}}</label>
                <input class="form-control" id="entry2" name="entry2" type="text" placeholder="{{__('admin_pricing.entry2_placeholder')}}" value="{{ $price->entry2 }}">
              </div>
              <div class="mb-3">
                <label class="col-form-label p-0" for="entry3">{{__('admin_pricing.entry3')}}</label>
                <input class="form-control" id="entry3" name="entry3" type="text" placeholder="{{__('admin_pricing.entry3_placeholder')}}" value="{{ $price->entry3 }}">
              </div>
              <div class="mb-3">
                <label class="col-form-label p-0" for="entry4">{{__('admin_pricing.entry4')}}</label>
                <input class="form-control" id="entry4" name="entry4" type="text" placeholder="{{__('admin_pricing.entry4_placeholder')}}" value="{{ $price->entry4 }}">
              </div>
              <div class="mb-3">
                <label class="col-form-label p-0" for="entry5">{{__('admin_pricing.entry5')}}</label>
                <input class="form-control" id="entry5" name="entry5" type="text" placeholder="{{__('admin_pricing.entry5_placeholder')}}" value="{{ $price->entry5 }}">
              </div>
              <div class="mb-3">
                <label class="col-form-label p-0" for="entry6">{{__('admin_pricing.entry6')}}</label>
                <input class="form-control" id="entry6" name="entry6" type="text" placeholder="{{__('admin_pricing.entry6_placeholder')}}" value="{{ $price->entry6 }}">
              </div>
              <div class="mb-3">
                <label class="col-form-label p-0" for="entry7">{{__('admin_pricing.entry7')}}</label>
                <input class="form-control" id="entry7" name="entry7" type="text" placeholder="{{__('admin_pricing.entry7_placeholder')}}" value="{{ $price->entry7 }}">
              </div>
              <div class="mb-3">
                <label class="col-form-label p-0" for="entry8">{{__('admin_pricing.entry8')}}</label>
                <input class="form-control" id="entry8" name="entry8" type="text" placeholder="{{__('admin_pricing.entry8_placeholder')}}" value="{{ $price->entry8 }}">
              </div>
              <div class="mb-3">
                <label class="col-form-label p-0" for="entry9">{{__('admin_pricing.entry9')}}</label>
                <input class="form-control" id="entry9" name="entry9" type="text" placeholder="{{__('admin_pricing.entry9_placeholder')}}" value="{{ $price->entry9 }}">
              </div>
              <div class="mb-3">
                <label class="col-form-label p-0" for="entry10">{{__('admin_pricing.entry10')}}</label>
                <input class="form-control" id="entry10" name="entry10" type="text" placeholder="{{__('admin_pricing.entry10_placeholder')}}" value="{{ $price->entry10 }}">
              </div>
              <div>
                <div class="form-check form-switch my-0">
                  <input class="form-check-input custom-switch" type="checkbox" name="is_active" id="is_active" @if ($price->is_active) checked @endif>
                  <label class="form-check-label custom-switch-label" for="is_active">{{__('admin_pricing.is_active')}}</label>
                </div>
              </div>
              <div class="mb-4">
                <div class="row">
                  <div class="col-md-6 col-lg-8 col-xl-8 col-xxl-9"></div>
                  <div class="col-md-6 col-lg-4 col-xl-4 col-xxl-3">
                    <div class="input-group" >
                      <input style="width:100%;" type="submit" class="btn btn btn-primary my-0 mx-1" id="updatepricingsettings" name="updatepricingsettings" value="{{__('admin_pricing.update')}}">
                    </div>
                  </div>
                </div>
              </div>
            </form>
          </div>
          <div class="tab-pane fade @if (session()->get('tab_page') == 'image_large') show active @endif" id="top-image-large" role="tabpanel" aria-labelledby="profile-top-tab">
            <form action="{{ route('admin.pricing-settings.update',[$price->id]) }}" method="POST" enctype="multipart/form-data">
              @csrf
              @method('PUT')
              <input type="hidden" name="page" value="{{ request('page') }}">
              <!-- Image Cropper -->
              <div class="row items-push d-flex align-items-stretch">
                <div class="col-xxl-6">
                  <h4 class="border-bottom pb-2">{{__('admin_pricing.large_screen_image')}}</h4>
                  <!--
                    RATIO & WIDTH Settings.
                  -->
                  <input type="hidden" class="img-w" value="{{ $large_size->width }}">
                  <input type="hidden" class="img-h" value="{{ $large_size->height }}">
                  <input type="hidden" class="ratio" value="{{ $large_size->ratio }}">
                  <div class="original text-center">
                    @if ($price->image_large_screen)
                    <img id="js-img-cropper" class="img-fluid" src="{{ asset($price->image_large_screen) }}" alt="photo">
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
                      data-method="crop" title="{{__('admin_pricing.crop_popup')}}">
                      {{__('admin_pricing.crop')}}
                      </button>
                    </div>
                  </div>
                  <div class="my-4">
                    <input type="file" class="form-control" id="image" name="image_large_screen" accept="image/*">
                    <small class="fw-light text-gray-dark">{{__('admin_pricing.file_mimes')}}</small>
                    <input type="hidden" name="cropped_data" id="cropped_data"> <!--base64 data transferi-->
                  </div>
                </div>

                <div id="box-2" class="col-xxl-6 d-none">
                  <h4 class="border-bottom pb-2">{{__('admin_pricing.preview')}}</h4>
                  <!--rightbox-->
                  <div class="img-result text-center">
                    <!-- Result of crop -->
                    <img class="cropped img-fluid cropped-preview" src="" alt="">
                  </div>
                  <div class="row">
                    <input type="submit" class="btn btn-lg btn-primary updateimagebutton" id="updateimagelarge" name="updateimagelarge" value="{{__('admin_pricing.upload_image')}}">
                  </div>
                </div>
              </div>
              <!-- END Image Cropper -->
            </form>
          </div>
          <div class="tab-pane fade @if (session()->get('tab_page') == 'image_small') show active @endif" id="top-image-small" role="tabpanel" aria-labelledby="contact-top-tab">
            <form action="{{ route('admin.pricing-settings.update',[$price->id]) }}" method="POST" enctype="multipart/form-data">
              @csrf
              @method('PUT')
              <input type="hidden" name="page" value="{{ request('page') }}">
              <!-- Image Cropper -->
              <div class="row items-push d-flex align-items-stretch">
                <div class="col-xxl-6">
                  <h4 class="border-bottom pb-2">{{__('admin_pricing.small_screen_image')}}</h4>
                  <!--
                    RATIO & WIDTH Settings.
                  -->
                    <input type="hidden" class="img-w-mobile" value="{{ $mobile_size->width }}">
                    <input type="hidden" class="img-h-mobile" value="{{ $mobile_size->height }}">
                    <input type="hidden" class="ratio-mobile" value="{{ $mobile_size->ratio }}">
                  <div class="original-mobile text-center">
                    @if ($price->image_small_screen)
                    <img id="js-img-cropper" class="img-fluid" src="{{ asset($price->image_small_screen) }}" alt="photo">
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
                      data-method="crop" title="{{__('admin_pricing.crop_popup')}}">
                      {{__('admin_pricing.crop')}}
                      </button>
                    </div>
                  </div>
                  <div class="my-4">
                    <input type="file" class="form-control" id="image-mobile" name="image_small_screen" accept="image/*">
                    <small class="fw-light text-gray-dark">{{__('admin_pricing.file_mimes')}}</small>
                    <input type="hidden" name="cropped_data_mobile" id="cropped_data_mobile"> <!--base64 data transferi-->
                  </div>
                </div>

                <div id="box-2-mobile" class="col-xxl-6 d-none">
                  <h4 class="border-bottom pb-2">{{__('admin_pricing.preview')}}</h4>
                  <!--rightbox-->
                  <div class="img-result-mobile text-center">
                    <!-- Result of crop -->
                    <img class="cropped-mobile img-fluid cropped-preview" src="" alt="">
                  </div>
                  <div class="row">
                    <input type="submit" class="btn btn-lg btn-primary updateimagebutton" id="updateimagesmall" name="updateimagesmall" value="{{__('admin_pricing.upload_image')}}">
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
<script>
  ClassicEditor
      .create( document.querySelector( '#description' ))
      .catch( error => {
          console.error( error );
      } );
</script>

@endsection
