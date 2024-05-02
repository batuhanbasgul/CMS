@extends('admin.layouts.base')

@section('title',__('admin_popup.create_content'))

@section('content')
<!-- Main Container -->
<div class="page-body pt-5">
  <div class="container-fluid">
    <div class="page-header">
      <div class="row">
        <div class="col-7">
          <h3>{{__('admin_popup.create_content')}}@if(count($langs) != 1)<span class="title-lang-code">({{ request('lang_code') }})</span>@endif</h3>
          <ol class="breadcrumb">
            <li class="breadcrumb-item disappear-500"><a href="{{ route('admin.index') }}"><ins> {{__('admin_popup.main_page')}}</ins></a></li>
            <li class="breadcrumb-item disappear-500">{{__('admin_popup.popup')}}</li>
            <li class="breadcrumb-item disappear-500">{{__('admin_popup.create_content')}}</li>
          </ol>
        </div>
        <div class="col-5 d-flex justify-content-end">
        </div>
      </div>
    </div>
  </div>
  <!-- Sweet Alert -->
  @if ($errors->any())
    @error('lang_code')
      <div>
        <script>swal("{{__('admin_popup.error')}}", "{{__('admin_popup.create_error_context')}}", "error");</script>
      </div>
    @enderror
  @else
    @if(session('success'))
    <script>swal("{{__('admin_popup.success')}}", "{{__('admin_popup.create_success_context')}}", "success");</script>
    @elseif(session('error'))
    <script>swal("{{__('admin_popup.error')}}", "{{__('admin_popup.create_error_context')}}", "error");</script>
    @endif
  @endif
  <!-- Container-fluid starts-->
  <div class="container-fluid">
    <div class="card">
      <div class="card-body">
        <form class="theme-form" action="{{ route('admin.popup-settings.store') }}" method="POST">
          @csrf
          <input type="hidden" name="lang_code" value="{{  request('lang_code') }}">
          <div class="mb-3">
            <label class="col-form-label p-0" for="title">{{__('admin_popup.title')}}</label>
            <input class="form-control" id="title" name="title" type="text" placeholder="{{__('admin_popup.title_placeholder')}}" value="{{ old('title') }}">
          </div>
          <div class="mb-3">
            <label class="col-form-label p-0" for="subtitle">{{__('admin_popup.subtitle')}}</label>
            <input class="form-control" id="subtitle" name="subtitle" type="text" placeholder="{{__('admin_popup.subtitle_placeholder')}}" value="{{ old('subtitle') }}">
          </div>
          <div class="mb-3">
            <label class="col-form-label p-0" for="video_embed_code">{{__('admin_popup.youtube_code')}}</label>
            <input class="form-control" id="video_embed_code" name="video_embed_code" type="text" placeholder="{{__('admin_popup.youtube_code_placeholder')}}" value="{{ old('video_embed_code') }}">
          </div>
          <div class="mb-3">
            <label class="col-form-label p-0" for="description">{{__('admin_popup.description')}}</label>
            <textarea class="form-control" id="description" name="description" rows="12" placeholder="{{__('admin_popup.description_placeholder')}}">{{ old('description') }}</textarea>
          </div>
          <div class="mb-3">
            <label class="col-form-label p-0" for="short_description">{{__('admin_popup.short_description')}}</label>
            <textarea class="form-control" id="short_description" name="short_description" rows="6" placeholder="{{__('admin_popup.short_description_placeholder')}}">{{ old('short_description') }}</textarea>
          </div>
          <div class="mb-3">
            <label class="col-form-label text-end p-0" for="start_date">{{__('admin_popup.start_date')}}</label>
            <input class="datepicker-here form-control digits" type="text" data-language="tr" id="start_date" name="start_date"
            placeholder="{{__('admin_popup.start_date_placeholder')}}" data-alt-input="true" data-date-format="dd MM yyyy" data-alt-format="j F Y">
          </div>
          <div class="mb-3">
            <label class="col-form-label text-end p-0" for="end_date">{{__('admin_popup.end_date')}}</label>
            <input class="datepicker-here form-control digits" type="text" data-language="tr" id="end_date" name="end_date"
            placeholder="{{__('admin_popup.end_date_placeholder')}}" data-alt-input="true" data-date-format="dd MM yyyy" data-alt-format="j F Y">
          </div>
          <div>
            <div class="form-check form-switch my-0">
              <input class="form-check-input custom-switch" type="checkbox" name="is_desktop_active" id="is_desktop_active" checked>
              <label class="form-check-label custom-switch-label" for="is_desktop_active">{{__('admin_popup.large_screen_active')}}</label>
            </div>
          </div>
          <div class="mb-4">
            <div class="form-check form-switch my-0">
              <input class="form-check-input custom-switch" type="checkbox" name="is_mobile_active" id="is_mobile_active" checked>
              <label class="form-check-label custom-switch-label" for="is_mobile_active">{{__('admin_popup.small_screen_active')}}</label>
            </div>
          </div>
          <div class="my-4">
            <div class="row">
              <div class="col-md-6 col-lg-8 col-xl-8 col-xxl-9"></div>
              <div class="col-md-6 col-lg-4 col-xl-4 col-xxl-3">
                <div class="input-group" >
                  <input style="width:100%;" type="submit" class="btn btn-primary" id="addpopup" data-toggle="click-ripple" name="addpopup" value="{{__('admin_popup.save')}}">
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






