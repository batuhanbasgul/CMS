@extends('admin.layouts.base')

@section('title',__('admin_gallery.update_content'))

@section('content')
<!-- Main Container -->
<div class="page-body pt-5">
  <div class="container-fluid">
    <div class="page-header">
      <div class="row">
        <div class="col-7">
          <h3>{{__('admin_gallery.update_content')}}</h3>
          <ol class="breadcrumb">
            <li class="breadcrumb-item disappear-500"><a href="{{ route('admin.index') }}"><ins> {{__('admin_gallery.main_page')}}</ins></a></li>
            <li class="breadcrumb-item disappear-500">{{__('admin_gallery.image_content')}}</li>
            <li class="breadcrumb-item disappear-500">{{__('admin_gallery.update_content')}}</li>
          </ol>
        </div>
        <div class="col-5 d-flex justify-content-end">
          <a href="{{ route('admin.gallery-settings.index',['lang_code'=> $image->lang_code , 'menu_code' => $image->menu_code]) }}">
            <button type="button" class="btn btn-primary btn-sm m-1">
                <i class="me-2 fa-solid fa-angles-left"></i><span> {{__('admin_gallery.turn_back')}}</span>
            </button>
          </a>
        </div>
      </div>
    </div>
  </div>
  <!-- Sweet Alert -->
  @if(session('success'))
  <script>swal("{{__('admin_gallery.success')}}", "{{__('admin_gallery.update_success_context')}}", "success");</script>
  @elseif(session('error'))
  <script>swal("{{__('admin_gallery.error')}}", "{{__('admin_gallery.update_error_context')}}", "error");</script>
  @elseif(session('file_extension_error'))
  <script>swal("{{__('admin_gallery.error')}}", "{{__('admin_gallery.update_error_file_mime')}}", "error");</script>
  @elseif(session('file_size_error'))
  <script>swal("{{__('admin_gallery.error')}}", "{{__('admin_gallery.update_error_file_size')}}", "error");</script>
  @elseif(session('no_image_error'))
  <script>swal("{{__('admin_gallery.error')}}", "{{__('admin_gallery.update_error_no_image')}}", "error");</script>
  @endif
  <!-- Container-fluid starts-->
  <div class="container-fluid">
    <div class="card">
      <div class="card-body">
        <form action="{{ route('admin.gallery-settings.update',[$image->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <input type="hidden" name="lang_code" value="{{ request('lang_code') }}">
            <input type="hidden" name="menu_code" value="{{ request('menu_code') }}">
            <!-- Image Cropper -->
            <div class="row items-push d-flex align-items-stretch">
              <div class="col-xxl-6">
                <h4 class="border-bottom pb-2">{{__('admin_gallery.update_content')}}</h4>
                <!--
                  RATIO & WIDTH Settings.
                -->
                <input type="hidden" class="img-w" value="{{ $image_size->width }}">
                <input type="hidden" class="img-h" value="{{ $image_size->height }}">
                <input type="hidden" class="ratio" value="{{ $image_size->ratio }}">
                <div class="original text-center">
                  <img id="js-img-cropper" class="img-fluid" src="{{ asset($image->image) }}" alt="photo">
                </div>
                <div class="text-center">
                  <p class="my-2" class="fw-ligth">{{ $image_size->width }}x{{ $image_size->height }} px</p>
                </div>
                <div id="box" class="block-content text-center d-none cropper-buttons">
                  <div class="row">
                    <button type="button" class="btn btn-primary crop" data-toggle="cropper"
                    data-method="crop" title="{{__('admin_gallery.crop_popup')}}">
                    {{__('admin_gallery.crop')}}
                    </button>
                  </div>
                </div>
                <div class="my-4">
                    <input type="file" class="form-control" id="image" name="image" accept="image/*">
                    <small class="fw-light text-gray-dark">{{__('file_mimes')}}</small>
                    <input type="hidden" name="cropped_data" id="cropped_data"> <!--base64 data transferi-->
                </div>
                <!-- INPUTS -->
                <div class="mb-3">
                  <label class="col-form-label p-0" for="title">{{__('admin_gallery.title')}}</label>
                  <input class="form-control" id="title" name="title" type="text" placeholder="{{__('admin_gallery.title_placeholder')}}" value="{{ $image->title }}">
                </div>
                <div class="mb-3">
                  <label class="col-form-label p-0" for="description">{{__('admin_gallery.description')}}</label>
                  <textarea class="form-control" id="description" name="description" rows="8" placeholder="{{__('admin_gallery.description_placeholder')}}">{{ $image->description }}</textarea>
                </div>
                <div class="mb-5">
                  <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="is_active" id="is_active" @if ($image->is_active) checked @endif style="width: 40px; height: 20px;">
                    <label class="form-check-label" for="is_active" style="margin-left:8px;">{{__('admin_gallery.is_active')}}</label>
                  </div>
                </div>
                <div class="row">
                  <input type="submit" class="btn btn-lg btn-primary updateimagebutton" id="updateimagelarge" name="updateimagelarge" value="{{__('admin_gallery.update_image')}}">
                </div>
                </div>
              <div id="box-2" class="col-xxl-6 d-none">
                <h4 class="border-bottom pb-2">{{__('admin_gallery.preview')}}</h4>
                <!--rightbox-->
                <div class="img-result text-center">
                  <!-- Result of the crop -->
                  <img class="cropped img-fluid cropped-preview" src="" alt="">
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
<script>
  ClassicEditor
      .create( document.querySelector( '#description' ) )
      .catch( error => {
          console.error( error );
      } );
</script>
