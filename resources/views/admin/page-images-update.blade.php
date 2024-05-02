@extends('admin.layouts.base')

@section('title',__('admin_page_images.update_content'))

@section('content')
<!-- Main Container -->
<div class="page-body pt-5">
  <div class="container-fluid">
    <div class="page-header">
      <div class="row">
        <div class="col-7">
          <h3>{{__('admin_page_images.update_page_image')}}</h3>
          <ol class="breadcrumb">
            <li class="breadcrumb-item disappear-500"><a href="{{ route('admin.index') }}"><ins>{{__('admin_page_images.main_page')}}</ins></a></li>
            <li class="breadcrumb-item disappear-500">{{__('admin_page_images.page_image')}}</li>
            <li class="breadcrumb-item disappear-500">{{__('admin_page_images.update_page_image')}}</li>
          </ol>
        </div>
        <div class="col-5 d-flex justify-content-end">
          <a class="mx-5" href="{{ route('admin.page-settings.index', ['lang_code' => request('lang_code')]) }}">
            <button type="button" class="btn btn-primary btn-sm m-1">
                <i class="me-2 fa-solid fa-angles-left"></i><span>{{__('admin_page_images.turn_back')}}</span>
            </button>
          </a>
        </div>
      </div>
    </div>
  </div>
  <!-- Sweet Alert -->
  @if(session('success'))
  <script>swal("{{__('admin_page_images.success')}}", "{{__('admin_page_images.update_success_context')}}", "success");</script>
  @elseif(session('error'))
  <script>swal("{{__('admin_page_images.error')}}", "{{__('admin_page_images.update_error_context')}}", "error");</script>
  @elseif(session('file_extension_error'))
  <script>swal("{{__('admin_page_images.error')}}", "{{__('admin_page_images.update_error_file_mime')}}", "error");</script>
  @elseif(session('file_size_error'))
  <script>swal("{{__('admin_page_images.error')}}", "{{__('admin_page_images.update_error_file_size')}}", "error");</script>
  @endif
  <!-- Container-fluid starts-->
  <div class="container-fluid">
    <div class="card">
      <div class="card-body">
        <form action="{{ route('admin.page-images.update',[$image->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <!-- Image Cropper -->
            <div class="row items-push d-flex align-items-stretch">
              <div class="col-xxl-6">
                <h4 class="border-bottom pb-2">{{__('admin_page_images.image')}}</h4>
                <!--
                  RATIO & WIDTH Settings.
                -->
                <input type="hidden" class="img-w-multi" value="{{ $image_size->width }}">
                <input type="hidden" class="img-h-multi" value="{{ $image_size->height }}">
                <input type="hidden" class="ratio-multi" value="{{ $image_size->ratio }}">
                <div class="original-multi text-center">
                  <img id="js-img-cropper" class="img-fluid" src="{{ asset($image->image) }}" alt="photo">
                </div>
                <div class="text-center">
                  <p class="my-2" class="fw-ligth">{{ $image_size->width }}x{{ $image_size->height }} px</p>
                </div>
                <div id="box-multi" class="block-content text-center d-none cropper-buttons">
                  <div class="row">
                    <button type="button" class="btn btn-primary crop-multi" data-toggle="cropper-multi"
                    data-method="crop-multi" title="{{__('admin_page_images.crop_popup')}}">
                    {{__('admin_page_images.crop')}}
                    </button>
                  </div>
                </div>
                <div class="my-4">
                    <input type="file" class="form-control" id="image-multi" name="image" accept="image/*">
                    <small class="fw-light text-gray-dark">{{__('admin_page_images.file_mimes')}}</small>
                    <input type="hidden" name="cropped_data_multi" id="cropped_data_multi"> <!--base64 data transferi-->
                </div>
                <div>
                  <div class="form-check form-switch my-0">
                    <input class="form-check-input custom-switch" type="checkbox" name="is_active" id="is_active" @if ($image->is_active) checked @endif>
                    <label class="form-check-label custom-switch-label" for="is_active">{{__('admin_page_images.is_active')}}</label>
                  </div>
                </div>
                <div class="row">
                  <input type="submit" class="btn btn-lg btn-primary updateimagebutton" id="updateimagemulti" name="updateimagemulti" value="{{__('admin_page_images.update')}}">
                </div>
              </div>
              <div id="box-2-multi" class="col-xxl-6 d-none">
                <h4 class="border-bottom pb-2">{{__('admin_page_images.preview')}}</h4>
                <!--rightbox-->
                <div class="img-result-multi text-center">
                  <!-- Result of the crop -->
                  <img class="cropped-multi img-fluid cropped-preview" src="" alt="">
                </div>
              </div>
            </div>
            <!-- END Image Cropper -->
          </form>
      </div>
    </div>
  </div>
  <!-- Container-fluid Ends-->
</div>
<!-- END Main Container -->

@endsection
