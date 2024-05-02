@extends('admin.layouts.base')

@section('title',__('admin_app_maintenance.update_content'))

@section('content')
<!-- Main Container -->
<div class="page-body pt-5">
  <div class="container-fluid">
    <div class="page-header">
      <div class="row">
        <div class="col-7">
            <div class="row">
                <div class="col-7">
                    <h3>{{__('admin_app_maintenance.update_content')}} @if(count($langs) != 1)<span class="title-lang-code">({{ request('lang_code') }})</span>@endif</h3>
                    <ol class="breadcrumb">
                      <li class="breadcrumb-item disappear-500"><a href="{{ route('admin.index') }}"><ins>{{__('admin_app_maintenance.main_page')}}</ins></a></li>
                      <li class="breadcrumb-item disappear-500">{{__('admin_app_maintenance.main_page_maintenance')}}</li>
                      <li class="breadcrumb-item disappear-500">{{__('admin_app_maintenance.update_content')}}</li>
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
                          <span class="disabled">({{__('admin_app_maintenance.current_language')}})</span>
                          <img src="{{ asset($lang->icon) }}" alt="">
                        </div>
                        @endif
                      @endforeach
                      <select class="form-select" id="lang-select" name="lang-select" onchange="window.location.href=this.options[this.selectedIndex].value;">
                          @foreach ($langs as $lang)
                          @if ($lang->is_active)
                          <option value="{{ route('admin.app-maintenance.index',['lang_code'=>$lang->lang_code, 'page'=>request('page')]) }}"
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
  @if(session('success'))
  <script>swal("{{__('admin_app_maintenance.success')}}", "{{__('admin_app_maintenance.update_success_context')}}", "success");</script>
  @elseif(session('error'))
  <script>swal("{{__('admin_app_maintenance.error')}}", "{{__('admin_app_maintenance.update_error_context')}}", "error");</script>
  @elseif(session('file_extension_error'))
  <script>swal("{{__('admin_app_maintenance.error')}}", "{{__('admin_app_maintenance.update_error_file_mime')}}", "error");</script>
  @elseif(session('file_size_error'))
  <script>swal("{{__('admin_app_maintenance.error')}}", "{{__('admin_app_maintenance.update_error_file_size')}}", "error");</script>
  @endif
  <!-- Container-fluid starts-->
  <div class="container-fluid">
    <div class="card">
      <div class="card-body">
        <ul class="nav nav-tabs border-tab" id="top-tab" role="tablist">
          <li class="nav-item"><a class="nav-link @if (!session('tab_page') || session()->get('tab_page') == 'page_content')) active @endif" data-bs-toggle="tab"
              href="#top-page-content" role="tab" aria-controls="top-page-content" aria-selected="true">
              <i class="fas fa-book-open"></i>{{__('admin_app_maintenance.content')}}</a></li>
          <li class="nav-item"><a class="nav-link @if (session()->get('tab_page') == 'page_image_large') active @endif" data-bs-toggle="tab"
              href="#top-page-image-large" role="tab" aria-controls="top-page-image-large" aria-selected="false">
              <i class="fa-solid fa-image"></i>{{__('admin_app_maintenance.image')}}</a></li>
        </ul>
        <div class="tab-content" id="top-tabContent">
          <div class="tab-pane fade @if (!session()->get('tab_page') || session()->get('tab_page') == 'page_content')) show active @endif" id="top-page-content" role="tabpanel" aria-labelledby="top-page-content-tab">
            <form class="theme-form needs-validation" novalidate="" action="{{ route('admin.app-maintenance.update',[$content->id]) }}" method="POST">
              @csrf
              @method('PUT')
              <input type="hidden" name="page" value="{{ request('page') }}">
              <input type="hidden" name="lang_code" value="{{ request('lang_code') }}">
              <h4 class="border-bottom pb-2">{{__('admin_app_maintenance.update_content')}}</h4>
              <div class="mb-3">
                <label class="col-form-label p-0" for="title">{{__('admin_app_maintenance.title')}}</label><span class="text-danger">*</span>
                <input class="form-control" id="title" name="title" type="text" placeholder="{{__('admin_app_maintenance.title_placeholder')}}" value="{{ $content->title }}" required="">
                <div class="valid-feedback">{{__('admin_app_maintenance.looks_good')}}</div>
              </div>
              <div class="mb-3">
                <label class="col-form-label text-end p-0" for="start_date">{{__('admin_app_maintenance.date')}}</label>
                <input class="datepicker-here form-control digits" type="text" data-language="tr" id="start_date" name="start_date"
                placeholder="{{__('admin_app_maintenance.date_placeholder')}}" data-alt-input="true" data-date-format="dd MM yyyy" data-alt-format="j F Y"  value="{{ $content->start_date }}">
              </div>
              <div class="mb-3">
                <label class="col-sm-3 col-form-label pt-0">{{__('admin_app_maintenance.color_code')}}</label>
                <div class="col-sm-9">
                  <input class="form-control form-control-color" type="color" id="color" name="color" value="{{ $content->color }}" required="">
                  <div class="valid-feedback">{{__('admin_app_maintenance.looks_good')}}</div>
                </div>
              </div>
              <div class="mb-3">
                <label class="col-form-label p-0" for="long_description">{{__('admin_app_maintenance.description')}}</label>
                <textarea class="form-control" id="long_description" name="long_description" rows="12" placeholder="{{__('admin_app_maintenance.description_placeholder')}}">{{ $content->description }}</textarea>
              </div>
              <div class="mb-3">
                <label class="col-form-label p-0" for="short_description">{{__('admin_app_maintenance.short_description')}}</label>
                <textarea class="form-control" id="short_description" name="short_description" rows="9" placeholder="{{__('admin_app_maintenance.short_description_placeholder')}}">{{ $content->short_description }}</textarea>
              </div>
              <div class="mb-4">
                <div class="row">
                  <div class="col-md-6 col-lg-8 col-xl-8 col-xxl-9"></div>
                  <div class="col-md-6 col-lg-4 col-xl-4 col-xxl-3">
                    <div class="input-group" >
                      <input style="width:100%;" type="submit" class="btn btn btn-primary my-0 mx-1" id="updateappmaintenance" name="updateappmaintenance" value="{{__('admin_app_maintenance.update')}}">
                    </div>
                  </div>
                </div>
              </div>
            </form>
          </div>
          <div class="tab-pane fade @if (session()->get('tab_page') == 'page_image_large') show active @endif" id="top-page-image-large" role="tabpanel" aria-labelledby="top-page-image-large">
            <form action="{{ route('admin.app-maintenance.update',[$content->id]) }}" method="POST" enctype="multipart/form-data">
              @csrf
              @method('PUT')
              <input type="hidden" name="page" value="{{ request('page') }}">
              <input type="hidden" name="lang_code" value="{{ request('lang_code') }}">
              <!-- Image Cropper -->
              <div class="row items-push d-flex align-items-stretch">
                <div class="col-xxl-6">
                  <h4 class="border-bottom pb-2">{{__('admin_app_maintenance.background')}}</h4>
                  <!--
                    RATIO & WIDTH Ayarları.
                  -->
                  <input type="hidden" class="img-w" value="{{ $image_size->width }}">
                  <input type="hidden" class="img-h" value="{{ $image_size->height }}">
                  <input type="hidden" class="ratio" value="{{ $image_size->ratio }}">
                  <div class="original text-center">
                    @if ($content->image)
                    <img id="js-img-cropper" class="img-fluid" src="{{ asset($content->image) }}" alt="photo">
                    @else
                    <img id="js-img-cropper" class="img-fluid" src="{{ asset('uploads/media/constant/placeholder.png') }}" alt="photo">
                    @endif
                  </div>
                  <div class="text-center">
                    <p class="my-2" class="fw-ligth">{{ $image_size->width }}x{{ $image_size->height }} px</p>
                  </div>
                  <div id="box" class="block-content text-center d-none cropper-buttons">
                    <div class="row">
                      <button type="button" class="btn btn-primary crop" data-toggle="cropper"
                      data-method="crop" title="{{__('admin_app_maintenance.crop_popup')}}">
                      {{__('admin_app_maintenance.crop')}}
                      </button>
                    </div>
                  </div>
                  <div class="my-4">
                    <input type="file" class="form-control" id="image" name="image" accept="image/*">
                    <small class="fw-light text-gray-dark">{{__('admin_app_maintenance.file_mimes')}}</small>
                    <input type="hidden" name="cropped_data" id="cropped_data"> <!--base64 data transferi-->
                  </div>
                </div>

                <div id="box-2" class="col-xxl-6 d-none">
                  <h4 class="border-bottom pb-2">{{__('admin_app_maintenance.preview')}}</h4>
                  <!--rightbox-->
                  <div class="img-result text-center">
                    <!-- result of crop -->
                    <img class="cropped img-fluid cropped-preview" src="" alt="">
                  </div>
                  <div class="row">
                    <input type="submit" class="btn btn-lg btn-primary updateimagebutton" id="updateimagelarge" name="updateimagelarge" value="{{__('admin_app_maintenance.upload_image')}}">
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
