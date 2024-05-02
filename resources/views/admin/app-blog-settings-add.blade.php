@extends('admin.layouts.base')

@section('title',__('admin_blog.create_content'))

@section('content')
<!-- Main Container -->
<div class="page-body pt-5">
  <div class="container-fluid">
    <div class="page-header">
      <div class="row">
        <div class="col-sm-5">
          <h3>{{__('admin_blog.create_content')}} @if(count($langs) != 1)<span class="title-lang-code">({{ request('lang_code') }})</span>@endif</h3>
          <ol class="breadcrumb">
            <li class="breadcrumb-item disappear-500"><a href="{{ route('admin.index') }}"><ins> Anasayfa</ins></a></li>
            <li class="breadcrumb-item disappear-500">{{__('admin_blog.main_page_announcements')}}</li>
            <li class="breadcrumb-item disappear-500">{{__('admin_blog.create_content')}}</li>
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
        <script>swal("{{__('admin_blog.error')}}", "{{__('admin_blog.create_error_context')}}", "error");</script>
      </div>
    @enderror
  @else
    @if(session('success'))
    <script>swal("{{__('admin_blog.success')}}", "{{__('admin_blog.create_success_context')}}", "success");</script>
    @elseif(session('error'))
    <script>swal("{{__('admin_blog.error')}}", "{{__('admin_blog.create_error_context')}}", "error");</script>
    @endif
  @endif
  <!-- Container-fluid starts-->
  <div class="container-fluid">
    <div class="card">
      <div class="card-body">
        <form class="theme-form needs-validation" novalidate="" action="{{ route('admin.app-blog-settings.store') }}" method="POST">
          @csrf
          <input type="hidden" name="lang_code" value="{{  request('lang_code') }}">
          <div class="mb-3">
            <label class="col-form-label p-0" for="title">{{__('admin_blog.title')}}</label><span class="text-danger">*</span>
            <input class="form-control" id="title" name="title" type="text" placeholder="{{__('admin_blog.title_placeholder')}}" value="{{ old('title') }}" required="">
            <div class="valid-feedback">{{__('admin_blog.looks_good')}}</div>
          </div>
          <div class="mb-3">
            <label class="col-form-label p-0" for="subtitle">{{__('admin_blog.subtitle')}}</label>
            <input class="form-control" id="subtitle" name="subtitle" type="text" placeholder="{{__('admin_blog.subtitle_placeholder')}}" value="{{ old('subtitle') }}">
          </div>
          <div class="mb-3">
            <label class="col-form-label p-0" for="description">{{__('admin_blog.description')}}</label>
            <textarea class="form-control" id="description" name="description" rows="12" placeholder="{{__('admin_blog.description_placeholder')}}">{{ old('description') }}</textarea>
          </div>
          <div class="mb-3">
            <label class="col-form-label p-0" for="short_description">{{__('admin_blog.short_description')}}</label>
            <textarea class="form-control" id="short_description" name="short_description" rows="6" placeholder="{{__('admin_blog.short_description_placeholder')}}">{{ old('short_description') }}</textarea>
          </div>
          <div class="mb-3">
            <label class="col-form-label p-0" for="blog_count">{{__('admin_blog.announcement_limit')}}</label>
            <input class="form-control" id="blog_count" name="blog_count" type="number" min="1" max="9" placeholder="{{__('admin_blog.announcement_limit_placeholder')}}" value="{{ old('blog_count') }}">
          </div>
          <div class="mb-5">
            <div class="form-check form-switch">
              <input class="form-check-input" type="checkbox" name="is_active" id="is_active" style="width: 40px; height: 20px;" checked>
              <label class="form-check-label" for="is_active" style="margin-left:8px;">{{__('admin_blog.is_active')}}</label>
            </div>
          </div>
          <div class="my-4">
            <div class="row">
              <div class="col-md-6 col-lg-8 col-xl-8 col-xxl-9"></div>
              <div class="col-md-6 col-lg-4 col-xl-4 col-xxl-3">
                <div class="input-group" >
                  <input style="width:100%;" type="submit" class="btn btn-primary" id="addappblog" data-toggle="click-ripple" name="addappblog" value="{{__('admin_blog.save')}}">
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






