@extends('admin.layouts.base')

@section('title',__('admin_page_settings.create_content'))

@section('content')
<!-- Main Container -->
<div class="page-body pt-5">
  <div class="container-fluid">
    <div class="page-header">
      <div class="row">
        <div class="col-sm-7">
          <h3>{{__('admin_page_settings.create_new_content')}} @if(count($langs) != 1)<span class="title-lang-code">({{ request('lang_code') }})</span>@endif</h3>
          <ol class="breadcrumb">
            <li class="breadcrumb-item disappear-500"><a href="{{ route('admin.index') }}"><ins> {{__('admin_page_settings.main_page')}}</ins></a></li>
            <li class="breadcrumb-item disappear-500">Sayfalar</li>
            <li class="breadcrumb-item disappear-500">{{__('admin_app_settings.create_content')}}</li>
          </ol>
        </div>
        <div class="col-5 d-flex justify-content-end">
          <a class="mx-5" href="{{ route('admin.page-settings.index', ['lang_code' => request('lang_code')]) }}">
            <button type="button" class="btn btn-primary btn-sm m-1">
                <i class="me-2 fa-solid fa-angles-left"></i><span>{{__('admin_page_settings.turn_back')}}</span>
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
        <script>swal("{{__('admin_page_settings.error')}}", "{{__('admin_page_settings.create_error_context')}}", "error");</script>
      </div>
    @enderror
  @else
    @if(session('success'))
    <script>swal("{{__('admin_page_settings.success')}}", "{{__('admin_page_settings.create_success_context')}}", "success");</script>
    @elseif(session('error'))
    <script>swal("{{__('admin_page_settings.error')}}", "{{__('admin_page_settings.create_error_context')}}", "error");</script>
    @endif
  @endif
  <!-- Container-fluid starts-->
  <div class="container-fluid">
    <div class="card">
      <div class="card-body">
        <form class="theme-form needs-validation" novalidate="" action="{{ route('admin.page-settings.store') }}" method="POST">
          @csrf
          <input type="hidden" name="lang_code" value="{{  request('lang_code') }}">
          <div class="mb-3">
            <label class="col-form-label p-0" for="title">{{__('admin_page_settings.title')}}</label><span class="text-danger">*</span>
            <input class="form-control" id="title" name="title" type="text" placeholder="{{__('admin_page_settings.title_placeholder')}}" value="{{ old('title') }}" required="">
            <div class="valid-feedback">{{__('admin_page_settings.looks_good')}}</div>
          </div>
          <div class="mb-3">
            <label class="col-form-label p-0" for="subtitle">{{__('admin_page_settings.subtitle')}}</label>
            <input class="form-control" id="subtitle" name="subtitle" type="text" placeholder="{{__('admin_page_settings.subtitle_placeholder')}}" value="{{ old('subtitle') }}">
          </div>
          <div class="mb-3">
            <label class="col-form-label p-0" for="author">{{__('admin_page_settings.author_name')}}</label>
            <input class="form-control" id="author" name="author" type="text" placeholder="{{__('admin_page_settings.author_name_placeholder')}}" value="{{ old('author') }}">
          </div>
          <div class="mb-3">
            <label class="col-form-label p-0" for="menu_name">{{__('admin_page_settings.menu_name')}}</label><span class="text-danger">*</span>
            <input class="form-control" id="menu_name" name="menu_name" type="text" placeholder="{{__('admin_page_settings.menu_name_placeholder')}}" value="{{ old('menu_name') }}" required="">
            <div class="valid-feedback">{{__('admin_page_settings.looks_good')}}</div>
          </div>
          <div class="mb-3">
            <label class="col-form-label text-end p-0" for="page_date">{{__('admin_page_settings.date')}}</label>
            <input class="datepicker-here form-control digits" type="text" data-language="tr" id="page_date" name="page_date"
            placeholder="{{__('admin_page_settings.date_placeholder')}}" data-alt-input="true" data-date-format="dd MM yyyy" data-alt-format="j F Y">
          </div>
          <div class="mb-3">
            <label class="col-form-label p-0" for="video_embed_code">{{__('admin_page_settings.youtube_code')}}</label>
            <input class="form-control" id="video_embed_code" name="video_embed_code" type="text" placeholder="{{__('admin_page_settings.youtube_code_placeholder')}}" value="{{ old('video_embed_code') }}">
          </div>
          <div class="mb-4">
            <label class="form-label p-0" for="keywords">{{__('admin_page_settings.keywords')}}</label>
            <textarea class="form-control" id="keywords" name="keywords" rows="4" placeholder="{{__('admin_page_settings.keywords_placeholder')}}">{{ old('keywords') }}</textarea>
          </div>
          <div class="mb-4">
            <label class="form-label p-0" for="description">{{__('admin_page_settings.description')}}</label>
            <textarea class="form-control" id="description" name="description" rows="8" placeholder="{{__('admin_page_settings.description_placeholder')}}">{{ old('description') }}</textarea>
          </div>
          <div class="mb-4">
            <label class="form-label p-0" for="short_description">{{__('admin_page_settings.short_description')}}</label>
            <textarea class="form-control" id="short_description" name="short_description" rows="6" placeholder="{{__('admin_page_settings.short_description_placeholder')}}">{{ old('short_description') }}</textarea>
          </div>
          <div class="my-4">
            <div class="row">
              <div class="col-md-6 col-lg-8 col-xl-8 col-xxl-9"></div>
              <div class="col-md-6 col-lg-4 col-xl-4 col-xxl-3">
                <div class="input-group" >
                  <input style="width:100%;" type="submit" class="btn btn-primary" id="addpage" data-toggle="click-ripple" name="addpage" value="{{__('admin_page_settings.save')}}">
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






