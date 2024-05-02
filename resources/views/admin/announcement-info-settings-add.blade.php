@extends('admin.layouts.base')

@section('title',__('admin_announcementinfo.create_content'))

@section('content')
<!-- Main Container -->
<div class="page-body pt-5">
  <div class="container-fluid">
    <div class="page-header">
      <div class="row">
        <div class="col-sm-7">
          <h3>{{__('admin_announcementinfo.create_content')}} @if(count($langs) != 1)<span class="title-lang-code">({{ request('lang_code') }})</span>@endif</h3>
          <ol class="breadcrumb">
            <li class="breadcrumb-item disappear-500"><a href="{{ route('admin.index') }}"><ins>{{__('admin_announcementinfo.main_page')}}</ins></a></li>
            <li class="breadcrumb-item disappear-500">{{__('admin_announcementinfo.announcements')}}</li>
            <li class="breadcrumb-item disappear-500">{{__('admin_announcementinfo.create_content')}}</li>
          </ol>
        </div>
        <div class="col-sm-5">
        </div>
      </div>
    </div>
  </div>
  <!-- Sweet Alert -->
  @if ($errors->any())
    @error('lang_code')
      <div>
        <script>swal("{{__('admin_announcementinfo.error')}}", "{{__('admin_announcementinfo.create_error_context')}}", "error");</script>
      </div>
    @enderror
  @else
    @if(session('success'))
    <script>swal("{{__('admin_announcementinfo.success')}}", "{{__('admin_announcementinfo.create_success_context')}}", "success");</script>
    @elseif(session('error'))
    <script>swal("{{__('admin_announcementinfo.error')}}", "{{__('admin_announcementinfo.create_error_context')}}", "error");</script>
    @endif
  @endif
  <!-- Container-fluid starts-->
  <div class="container-fluid">
    <div class="card">
      <div class="card-body">
        <form class="theme-form needs-validation" novalidate="" action="{{ route('admin.announcement-info-settings.store') }}" method="POST">
          @csrf
          <input type="hidden" name="lang_code" value="{{  request('lang_code') }}">
          <div class="mb-3">
            <label class="col-form-label p-0" for="title">{{__('admin_announcementinfo.title')}}</label><span class="text-danger">*</span>
            <input class="form-control" id="title" name="title" type="text" placeholder="{{__('admin_announcementinfo.title_placeholder')}}" value="{{ old('title') }}" required="">
            <div class="valid-feedback">{{__('admin_announcementinfo.looks_good')}}</div>
          </div>
          <div class="mb-3">
            <label class="col-form-label p-0" for="subtitle">{{__('admin_announcementinfo.subtitle')}}</label>
            <input class="form-control" id="subtitle" name="subtitle" type="text" placeholder="{{__('admin_announcementinfo.subtitle_placeholder')}}" value="{{ old('subtitle') }}">
          </div>
          <div class="mb-3">
            <label class="col-form-label p-0" for="menu_name">{{__('admin_announcementinfo.menu_name')}}</label><span class="text-danger">*</span>
            <input class="form-control" id="menu_name" name="menu_name" type="text" placeholder="{{__('admin_announcementinfo.menu_name_placeholder')}}" value="{{ old('menu_name') }}" required="">
            <div class="valid-feedback">{{__('admin_announcementinfo.looks_good')}}</div>
          </div>
          <div class="mb-3">
            <label class="col-form-label p-0" for="page_limit">{{__('admin_announcementinfo.page_announcement_limit')}}</label>
            <input class="form-control" id="page_limit" name="page_limit" type="number" placeholder="{{__('admin_announcementinfo.page_announcement_limit_placeholder')}}" value="{{ old('page_limit') }}">
          </div>
          <div class="mb-3">
            <label class="col-form-label p-0" for="author">{{__('admin_announcementinfo.author_name')}}</label>
            <input class="form-control" id="author" name="author" type="text" placeholder="{{__('admin_announcementinfo.author_name_placeholder')}}" value="{{ old('author') }}">
          </div>
          <div class="mb-3">
            <label class="col-form-label text-end p-0" for="announcement_date">{{__('admin_announcementinfo.date')}}</label>
            <input class="datepicker-here form-control digits" type="text" data-language="tr" id="announcement_date" name="announcement_date"
            placeholder="{{__('admin_announcementinfo.date_placeholder')}}" data-alt-input="true" data-date-format="dd MM yyyy" data-alt-format="j F Y">
          </div>
          <div class="mb-3">
            <label class="col-form-label p-0" for="video_embed_code">{{__('admin_announcementinfo.youtube_code')}}</label>
            <input class="form-control" id="video_embed_code" name="video_embed_code" type="text" placeholder="{{__('admin_announcementinfo.youtube_code_placeholder')}}" value="{{ old('video_embed_code') }}">
          </div>
          <div class="mb-4">
            <label class="form-label p-0" for="keywords">{{__('admin_announcementinfo.keywords')}}</label>
            <textarea class="form-control" id="keywords" name="keywords" rows="4" placeholder="{{__('admin_announcementinfo.keywords_placeholder')}}">{{ old('keywords') }}</textarea>
          </div>
          <div class="mb-4">
            <label class="form-label p-0" for="description">{{__('admin_announcementinfo.description')}}</label>
            <textarea class="form-control" id="description" name="description" rows="8" placeholder="{{__('admin_announcementinfo.description_placeholder')}}">{{ old('description') }}</textarea>
          </div>
          <div class="mb-4">
            <label class="form-label p-0" for="short_description">{{__('admin_announcementinfo.short_description')}}</label>
            <textarea class="form-control" id="short_description" name="short_description" rows="6" placeholder="{{__('admin_announcementinfo.short_description_placeholder')}}">{{ old('short_description') }}</textarea>
          </div>
          <div class="my-4">
            <div class="row">
              <div class="col-md-6 col-lg-8 col-xl-8 col-xxl-9"></div>
              <div class="col-md-6 col-lg-4 col-xl-4 col-xxl-3">
                <div class="input-group" >
                  <input style="width:100%;" type="submit" class="btn btn-primary" id="addannouncementinfo" data-toggle="click-ripple" name="addannouncementinfo" value="{{__('admin_announcementinfo.save')}}">
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






