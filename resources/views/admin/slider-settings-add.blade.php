@extends('admin.layouts.base')

@section('title',__('admin_slider.create_content'))

@section('content')
<!-- Main Container -->
<div class="page-body pt-5">
  <div class="container-fluid">
    <div class="page-header">
      <div class="row">
        <div class="col-sm-7">
          <h3>{{__('admin_slider.create_new_content')}} @if(count($langs) != 1)<span class="title-lang-code">({{ request('lang_code') }})</span>@endif</h3>
          <ol class="breadcrumb">
            <li class="breadcrumb-item disappear-500"><a href="{{ route('admin.index') }}"><ins>{{__('admin_slider.main_page')}}</ins></a></li>
            <li class="breadcrumb-item disappear-500">{{__('admin_slider.slider')}}</li>
            <li class="breadcrumb-item disappear-500">{{__('admin_slider.create_content')}}</li>
          </ol>
        </div>
        <div class="col-5 d-flex justify-content-end">
          <a class="mx-5" href="{{ route('admin.slider-settings.index', ['lang_code' => request('lang_code')]) }}">
            <button type="button" class="btn btn-primary btn-sm m-1">
                <i class="me-2 fa-solid fa-angles-left"></i><span>{{__('admin_slider.turn_back')}}</span>
            </button>
          </a>
        </div>
      </div>
    </div>
  </div>
  <!-- Sweet Alert -->
  @if ($errors->any())
    @error('lang_code')
      <div>
        <script>swal("{{__('admin_slider.error')}}", "{{__('admin_slider.create_error_context')}}", "error");</script>
      </div>
    @enderror
  @else
    @if(session('success'))
    <script>swal("{{__('admin_slider.success')}}", "{{__('admin_slider.create_success_context')}}", "success");</script>
    @elseif(session('error'))
    <script>swal("{{__('admin_slider.error')}}", "{{__('admin_slider.create_error_context')}}", "error");</script>
    @endif
  @endif
  <!-- Container-fluid starts-->
  <div class="container-fluid">
    <div class="card">
      <div class="card-body">
        <form class="theme-form needs-validation" novalidate="" action="{{ route('admin.slider-settings.store') }}" method="POST">
          @csrf
          <input type="hidden" name="lang_code" value="{{  request('lang_code') }}">
          <div class="mb-3">
            <label class="col-form-label p-0" for="title">{{__('admin_slider.title')}}</label><span class="text-danger">*</span>
            <input class="form-control" id="title" name="title" type="text" placeholder="{{__('admin_slider.title_placeholder')}}" value="{{ old('title') }}" required="">
            <div class="valid-feedback">{{__('admin_slider.looks_good')}}</div>
          </div>
          <div class="mb-3">
            <label class="col-form-label p-0" for="link">{{__('admin_slider.target_url')}}</label>
            <input class="form-control" id="link" name="link" type="text" placeholder="{{__('admin_slider.target_url_placeholder')}}" value="{{ old('link') }}">
          </div>
          <div class="mb-3">
            <label class="col-form-label p-0" for="video_embed_code">{{__('admin_slider.youtube_code')}}</label>
            <input class="form-control" id="video_embed_code" name="video_embed_code" type="text" placeholder="{{__('admin_slider.youtube_code_placeholder')}}" value="{{ old('video_embed_code') }}">
          </div>
          <div class="mb-3">
            <label class="col-form-label p-0" for="logo">{{__('admin_slider.logo')}}</label>
            <input class="form-control" id="logo" name="logo" type="text" placeholder="{{__('admin_slider.logo_placeholder')}}" value="{{ old('logo') }}">
          </div>
          <div class="mb-3">
            <label class="col-form-label p-0" for="logo_title">{{__('admin_slider.logo_title')}}</label>
            <input class="form-control" id="logo_title" name="logo_title" type="text" placeholder="{{__('admin_slider.logo_title_placeholder')}}" value="{{ old('logo_title') }}">
          </div>
          <div class="mb-4">
            <label class="form-label p-0" for="description">{{__('admin_slider.description')}}</label>
            <textarea class="form-control" id="description" name="description" rows="8" placeholder="{{__('admin_slider.description_placeholder')}}">{{ old('description') }}</textarea>
          </div>
          <div>
            <div class="form-check form-switch my-0">
              <input class="form-check-input custom-switch" type="checkbox" name="is_desktop_active" id="is_desktop_active" checked>
              <label class="form-check-label custom-switch-label" for="is_desktop_active">{{__('admin_slider.large_screen_active')}}</label>
            </div>
          </div>
          <div>
            <div class="form-check form-switch my-0">
              <input class="form-check-input custom-switch" type="checkbox" name="is_mobile_active" id="is_mobile_active" checked>
              <label class="form-check-label custom-switch-label" for="is_mobile_active">{{__('admin_slider.small_screen_active')}}</label>
            </div>
          </div>
          <div class="my-4">
            <div class="row">
              <div class="col-md-6 col-lg-8 col-xl-8 col-xxl-9"></div>
              <div class="col-md-6 col-lg-4 col-xl-4 col-xxl-3">
                <div class="input-group" >
                  <input style="width:100%;" type="submit" class="btn btn-primary" id="addslider" data-toggle="click-ripple" name="addslider" value="{{__('admin_slider.save')}}">
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!-- Container-fluid Ends-->
</div>
<!-- END Main Container -->
@endsection






