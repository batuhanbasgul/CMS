@extends('admin.layouts.base')

@section('title',__('admin_lang.create_content'))

@section('content')
<!-- Main Container -->
<div class="page-body pt-5">
  <div class="container-fluid">
    <div class="page-header">
      <div class="row">
        <div class="col-7">
          <h3>{{__('admin_lang.create_new_content')}}</h3>
          <ol class="breadcrumb">
            <li class="breadcrumb-item disappear-500"><a href="{{ route('admin.index') }}"><ins> {{__('admin_lang.main_page')}}</ins></a></li>
            <li class="breadcrumb-item disappear-500">{{__('admin_lang.languages')}}</li>
            <li class="breadcrumb-item disappear-500">{{__('admin_lang.create_content')}}</li>
          </ol>
        </div>
        <div class="col-5 d-flex justify-content-end">
        </div>
      </div>
    </div>
  </div>
  <!-- Sweet Alert -->
  @if(session('success'))
  <script>swal("{{__('admin_lang.success')}}", "{{__('admin_lang.create_success_context')}}", "success");</script>
  @elseif(session('error'))
  <script>swal("{{__('admin_lang.error')}}", "{{__('admin_lang.create_error_context')}}", "error");</script>
  @elseif(session('file_extension_error'))
  <script>swal("{{__('admin_lang.error')}}", "{{__('admin_lang.update_error_file_mime')}}", "error");</script>
  @elseif(session('file_size_error'))
  <script>swal("{{__('admin_lang.error')}}", "{{__('admin_lang.update_error_file_size')}}", "error");</script>
  @elseif(session('same_lang_code_error'))
  <script>swal("{{__('admin_lang.error')}}", "{{__('admin_lang.update_error_same_lang')}}", "error");</script>
  @endif
  <!-- Container-fluid starts-->
  <div class="container-fluid">
    <div class="card">
      <div class="card-body">
        <form class="theme-form needs-validation" novalidate="" action="{{ route('admin.lang-settings.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <!-- Image Cropper -->
            <div class="row items-push d-flex align-items-stretch">
              <div class="col-xxl-6">
                <h4 class="border-bottom pb-2">{{__('admin_lang.language_content')}}</h4>
                <!--
                  RATIO & WIDTH Settings.
                -->
                <input type="hidden" class="img-w-favicon" value="{{ $image_size->width }}">
                <input type="hidden" class="img-h-favicon" value="{{ $image_size->height }}">
                <input type="hidden" class="ratio-favicon" value="{{ $image_size->ratio }}">
                <div class="original-favicon text-center">
                  <img id="js-img-cropper" class="img-fluid" src="{{ asset('uploads/media/constant/placeholder.png') }}" alt="photo">
                </div>
                <div class="text-center">
                  <p class="my-2" class="fw-ligth">{{ $image_size->width }}x{{ $image_size->height }} px</p>
                </div>
                <div id="box-favicon" class="block-content text-center d-none cropper-buttons">
                  <div class="row">
                    <button type="button" class="btn btn-primary crop-favicon" data-toggle="cropper-favicon"
                    data-method="crop" title="{{__('admin_lang.crop_popup')}}">
                    {{__('admin_lang.crop')}}
                    </button>
                  </div>
                </div>
                <div class="my-4">
                    <input type="file" class="form-control" id="image-favicon" name="favicon" accept="image/*">
                    <small class="fw-light text-gray-dark">{{__('admin_lang.file_mimes')}}</small>
                    <input type="hidden" name="cropped_data_favicon" id="cropped_data_favicon"> <!--base64 data transfer-->
                </div>
              </div>
              <div id="box-2-favicon" class="col-xxl-6 d-none">
                <h4 class="border-bottom pb-2">{{__('admin_lang.preview')}}</h4>
                <!--rightbox-->
                <div class="img-result-favicon text-center">
                  <!-- Result of the crop -->
                  <img class="cropped-favicon img-fluid cropped-preview" src="" alt="">
                </div>
                <!-- INPUTS -->
                <div class="mx-4">
                <div class="mb-3">
                    <label class="col-form-label p-0" for="lang_name">{{__('admin_lang.lang_name')}}</label><span class="text-danger">*</span>
                    <input class="form-control" id="lang_name" name="lang_name" type="text" placeholder="{{__('admin_lang.lang_name_placeholder')}}" value="{{ old('lang_name') }}" required="">
                    <div class="valid-feedback">{{__('admin_lang.looks_good')}}</div>
                </div>
                <div class="mb-3">
                  <label class="col-form-label" for="lang_code">{{__('admin_lang.lang_code')}}</label><span class="text-danger">*</span>
                  <select class="js-example-basic-single col-sm-12" id="lang_code" name="lang_code" required>
                    @foreach ($langCodes as $langCode)
                    <option value="{{ $langCode }}">{{ $langCode }}</option>
                    @endforeach
                  </select>
                </div>
                <div>
                  <div class="form-check form-switch my-0">
                    <input class="form-check-input custom-switch" type="checkbox" name="is_active" id="is_active" checked>
                    <label class="form-check-label custom-switch-label" for="is_active">{{__('admin_lang.is_active')}}</label>
                  </div>
                </div>
                </div>
                <div class="row">
                  <input type="submit" class="btn btn-lg btn-primary updateimagebutton" id="addlanguage" name="addlanguage" value="{{__('admin_lang.create_lang')}}">
                </div>
              </div>
              <!-- END Image Cropper -->
            </div>
          </form>
      </div>
    </div>
  </div>
  <!-- Container-fluid Ends-->
</div>
<!-- END Main Container -->
@endsection
