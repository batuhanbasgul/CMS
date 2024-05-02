@extends('admin.layouts.base')

@section('title',__('admin_menu.update_content'))

@section('content')
<!-- Main Container -->
<div class="page-body pt-5">
  <div class="container-fluid">
    <div class="page-header">
      <div class="row">
        <div class="col-7">
            <h3>{{__('admin_menu.update_menu_content')}} @if(count($langs) != 1)<span class="title-lang-code">({{ request('lang_code') }})</span>@endif</h3>
            <ol class="breadcrumb">
              <li class="breadcrumb-item disappear-500"><a href="{{ route('admin.index') }}"><ins> {{__('admin_menu.main_page')}}</ins></a></li>
              <li class="breadcrumb-item disappear-500">{{__('admin_menu.menu')}}</li>
              <li class="breadcrumb-item disappear-500">{{__('admin_menu.update_menu_content')}}</li>
            </ol>
        </div>
        <div class="col-5 d-flex justify-content-end">
            <a href="{{ route('admin.menu-settings.index', ['lang_code' => $menu->lang_code, 'show' => request('show')]) }}" >
              <button type="button" class="btn btn-primary btn-sm m-1">
                <i class="me-2 fa-solid fa-angles-left"></i><span> {{__('admin_menu.turn_back')}}</span>
              </button>
            </a>
        </div>
      </div>
    </div>
  </div>
  <!-- Sweet Alert -->
  @if(session('success'))
  <script>swal("{{__('admin_menu.success')}}", "{{__('admin_menu.update_success_context')}}", "success");</script>
  @elseif(session('error'))
  <script>swal("{{__('admin_menu.error')}}", "{{__('admin_menu.update_error_context')}}", "error");</script>
  @elseif(session('file_extension_error'))
  <script>swal("{{__('admin_menu.error')}}", "{{__('admin_menu.update_error_file_mime')}}", "error");</script>
  @elseif(session('file_size_error'))
  <script>swal("{{__('admin_menu.error')}}", "{{__('admin_menu.update_error_file_size')}}", "error");</script>
  @endif
  <!-- Container-fluid starts-->
  <div class="container-fluid">
    <div class="card">
      <div class="card-body">
        <ul class="nav nav-tabs border-tab" id="top-tab" role="tablist">
          <li class="nav-item"><a class="nav-link @if (!session()->get('tab_page') || session()->get('tab_page') == 'context') active @endif" data-bs-toggle="tab"
              href="#top-content" role="tab" aria-controls="top-content" aria-selected="true">
              <i class="fas fa-book-open"></i>{{__('admin_menu.menu_content')}}</a></li>
          <li class="nav-item"><a class="nav-link @if (session()->get('tab_page') == 'logo') active @endif" data-bs-toggle="tab"
              href="#top-image-logo" role="tab" aria-controls="top-image-logo" aria-selected="false">
              <i class="fa-solid fa-image"></i>{{__('admin_menu.menu_logo')}}</a></li>
        </ul>
        <div class="tab-content" id="top-tabContent">
          <div class="tab-pane fade @if (!session()->get('tab_page') || session()->get('tab_page') == 'context') show active @endif" id="top-content" role="tabpanel" aria-labelledby="top-content-tab">
            <form class="theme-form needs-validation" novalidate="" action="{{ route('admin.menu-settings.update',[$menu->id]) }}" method="POST">
              @csrf
              @method('PUT')
              <input type="hidden" name="page" value="{{ request('page') }}">
              <h4 class="border-bottom pb-2">{{__('admin_menu.menu_content')}}</h4>
              <div class="mb-4">
                <label class="form-label p-0" for="menu_name">{{__('admin_menu.title')}}<span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="menu_name" name="menu_name" placeholder="{{__('admin_menu.title_placeholder')}}" value="{{ $menu->menu_name }}" required="">
                <div class="valid-feedback">{{__('admin_menu.looks_good')}}</div>
              </div>
              <div>
                <div class="form-check form-switch my-0">
                  <input class="form-check-input custom-switch" type="checkbox" name="is_desktop_active" id="is_desktop_active" @if ($menu->is_desktop_active) checked @endif>
                  <label class="form-check-label custom-switch-label" for="is_desktop_active">{{__('admin_menu.large_screen_active')}}</label>
                </div>
              </div>
              <div class="mb-4">
                <div class="form-check form-switch my-0">
                  <input class="form-check-input custom-switch" type="checkbox" name="is_mobile_active" id="is_mobile_active" @if ($menu->is_mobile_active) checked @endif>
                  <label class="form-check-label custom-switch-label" for="is_mobile_active">{{__('admin_menu.small_screen_active')}}</label>
                </div>
              </div>
              <div class="mb-4">
                <div class="row">
                  <div class="col-md-6 col-lg-8 col-xl-8 col-xxl-9"></div>
                  <div class="col-md-6 col-lg-4 col-xl-4 col-xxl-3">
                    <div class="input-group" >
                      <input style="width:100%;" type="submit" class="btn btn btn-primary my-0 mx-1" id="updatemenu" name="updatemenu" value="{{__('admin_menu.update')}}">
                    </div>
                  </div>
                </div>
              </div>
            </form>
          </div>
          <div class="tab-pane fade @if (session()->get('tab_page') == 'logo') show active @endif" id="top-image-logo" role="tabpanel" aria-labelledby="top-image-logo-tab">
            <form action="{{ route('admin.menu-settings.update',[$menu->id]) }}" method="POST" enctype="multipart/form-data">
              @csrf
              @method('PUT')
              <input type="hidden" name="page" value="{{ request('page') }}">
              <!-- Image Cropper -->
              <div class="row items-push d-flex align-items-stretch">
                <div class="col-xxl-6">
                  <h4 class="border-bottom pb-2">{{__('admin_menu.menu_logo')}}</h4>
                  <!--
                    RATIO & WIDTH Settings.
                  -->
                    <input type="hidden" class="img-w-favicon" value="{{ $image_size->width }}">
                    <input type="hidden" class="img-h-favicon" value="{{ $image_size->height }}">
                    <input type="hidden" class="ratio-favicon" value="{{ $image_size->ratio }}">
                  <div class="original-favicon text-center">
                    @if ($menu->menu_logo)
                    <img id="js-img-cropper" class="img-fluid" src="{{ asset($menu->menu_logo) }}" alt="photo">
                    @else
                    <img id="js-img-cropper" class="img-fluid" src="{{ asset('uploads/media/constant/placeholder.png') }}" alt="photo">
                    @endif
                  </div>
                  <div class="text-center">
                    <p class="my-2" class="fw-ligth">{{ $image_size->width }}x{{ $image_size->height }} px</p>
                  </div>
                  <div id="box-favicon" class="block-content text-center d-none cropper-buttons">
                    <div class="row">
                      <button type="button" class="btn btn-primary crop-favicon" data-toggle="cropper-favicon"
                      data-method="crop" title="{{__('admin_menu.crop_popup')}}">
                      {{__('admin_menu.crop')}}
                      </button>
                    </div>
                  </div>
                  <div class="my-4">
                    <input type="file" class="form-control" id="image-favicon" name="menu_logo" accept="image/*">
                    <small class="fw-light text-gray-dark">{{__('admin_menu.file_mimes')}}</small>
                    <input type="hidden" name="cropped_data_favicon" id="cropped_data_favicon"> <!--base64 data transferi-->
                  </div>
                </div>

                <div id="box-2-favicon" class="col-xxl-6 d-none">
                  <h4 class="border-bottom pb-2">{{__('admin_menu.preview')}}</h4>
                  <!--rightbox-->
                  <div class="img-result-favicon text-center">
                    <!-- Result of the crop -->
                    <img class="cropped-favicon img-fluid cropped-preview" src="" alt="">
                  </div>
                  <div class="row">
                    <input type="submit" class="btn btn-lg btn-primary updateimagebutton" id="updatelogo" name="updatelogo" value="{{__('admin_menu.upload_image')}}">
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
