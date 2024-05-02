@extends('admin.layouts.base')

@section('title',__('admin_app_settings.create_content'))

@section('content')
<!-- Main Container -->
<div class="page-body pt-5">
  <div class="container-fluid">
    <div class="page-header">
      <div class="row">
        <div class="col-sm-5">
          <h3>{{__('admin_app_settings.create_content')}} @if(count($langs) != 1)<span class="title-lang-code">({{ request('lang_code') }})</span>@endif</h3>
          <ol class="breadcrumb">
            <li class="breadcrumb-item disappear-500"><a href="{{ route('admin.index') }}"><ins> {{__('admin_app_settings.main_page')}}</ins></a></li>
            <li class="breadcrumb-item disappear-500">{{__('admin_app_settings.main_page_settings')}}</li>
            <li class="breadcrumb-item disappear-500">{{__('admin_app_settings.create_content')}}</li>
          </ol>
        </div>
        <div class="col-sm-7">
        </div>
      </div>
    </div>
  </div>
  <!-- Sweet Alert -->
  @if ($errors->any())
    @error('lang_code')
      <div>
        <script>swal("{{__('admin_app_settings.error')}}", "{{__('admin_app_settings.create_error_context')}}", "error");</script>
      </div>
    @enderror
  @else
    @if(session('success'))
    <script>swal("{{__('admin_app_settings.success')}}", "{{__('admin_app_settings.create_success_context')}}", "success");</script>
    @elseif(session('error'))
    <script>swal("{{__('admin_app_settings.error')}}", "{{__('admin_app_settings.create_error_context')}}", "error");</script>
    @endif
  @endif
  <!-- Container-fluid starts-->
  <div class="container-fluid">
    <div class="card">
      <div class="card-body">
        <form class="theme-form needs-validation" novalidate="" action="{{ route('admin.app-settings.store') }}" method="POST">
          @csrf
          <input type="hidden" name="lang_code" value="{{  request('lang_code') }}">
          <div class="mb-3">
            <label class="col-form-label p-0" for="title">{{__('admin_app_settings.title')}}</label><span class="text-danger">*</span>
            <input class="form-control" id="title" name="title" type="text" placeholder="{{__('admin_app_settings.title_placeholder')}}" value="{{ old('title') }}" required="">
            <div class="valid-feedback">{{__('admin_app_settings.looks_good')}}</div>
          </div>
          <div class="mb-3">
            <label class="col-form-label p-0" for="app_description">{{__('admin_app_settings.description')}}</label>
            <textarea class="form-control" id="app_description" name="app_description" rows="12" placeholder="{{__('admin_app_settings.description_placeholder')}}">{{ old('app_description') }}</textarea>
          </div>
          <div class="mb-3">
            <label class="col-form-label p-0" for="keywords">{{__('admin_app_settings.keywords')}}</label>
            <textarea class="form-control" id="keywords" name="keywords" rows="6" placeholder="{{__('admin_app_settings.keywords_placeholder')}}">{{ old('keywords') }}</textarea>
          </div>
          <div class="mb-3">
            <label class="col-form-label p-0" for="google_analytic">{{__('admin_app_settings.google_analytics')}}</label>
            <textarea class="form-control" id="google_analytic" name="google_analytic" rows="6" placeholder="{{__('admin_app_settings.google_analytics_placeholder')}}">{{ old('google_analytic') }}</textarea>
          </div>
          <div class="mb-3">
            <label class="col-form-label p-0" for="yandex_verification_code">{{__('admin_app_settings.yandex_verification')}}</label>
            <textarea class="form-control" id="yandex_verification_code" name="yandex_verification_code" rows="6" placeholder="{{__('admin_app_settings.yandex_verification_placeholder')}}">{{ old('yandex_verification_code') }}</textarea>
          </div>
          <div class="my-4">
            <div class="row">
              <div class="col-md-6 col-lg-8 col-xl-8 col-xxl-9"></div>
              <div class="col-md-6 col-lg-4 col-xl-4 col-xxl-3">
                <div class="input-group" >
                  <input style="width:100%;" type="submit" class="btn btn-primary" id="addappsettings" data-toggle="click-ripple" name="addappsettings" value="{{__('admin_app_settings.save')}}">
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
<script>
  ClassicEditor
      .create( document.querySelector( '#description' ) )
      .catch( error => {
          console.error( error );
      } );
</script>
<script>
  ClassicEditor
      .create( document.querySelector( '#short_description' ) )
      .catch( error => {
          console.error( error );
      } );
</script>
@endsection






