@extends('admin.layouts.base')

@section('title',__('admin_blog_reference.update_content'))

@section('content')
<!-- Main Container -->
<div class="page-body pt-5">
  <div class="container-fluid">
    <div class="page-header">
      <div class="row">
        <div class="col-sm-7">
            <div class="row">
                <div class="col-8">
                    <h3>{{__('admin_blog_reference.update_content')}} @if(count($langs) != 1)<span class="title-lang-code">({{ request('lang_code') }})</span>@endif</h3>
                    <ol class="breadcrumb">
                      <li class="breadcrumb-item disappear-500"><a href="{{ route('admin.index') }}"><ins> {{__('admin_blog_reference.main_page')}}</ins></a></li>
                      <li class="breadcrumb-item disappear-500">{{__('admin_blog_reference.main_page_references')}}</li>
                      <li class="breadcrumb-item disappear-500">{{__('admin_blog_reference.update_content')}}</li>
                    </ol>
                </div>
                <div class="col-4">
                    <!--
                      Refresh the page with selected lang_code.
                    -->
                    @if(count($langs) != 1)
                      @foreach ($langs as $lang)
                        @if ($lang_code==$lang->lang_code)
                        <div class="mx-2 d-none">
                          <span class="disabled">({{__('admin_blog_reference.current_language')}})</span>
                          <img src="{{ asset($lang->icon) }}" alt="">
                        </div>
                        @endif
                      @endforeach
                      <select class="form-select" id="lang-select" name="lang-select" onchange="window.location.href=this.options[this.selectedIndex].value;">
                          @foreach ($langs as $lang)
                          @if ($lang->is_active)
                          <option value="{{ route('admin.app-reference-settings.index',['lang_code'=>$lang->lang_code, 'page'=>request('page')]) }}"
                            @if ($lang_code==$lang->lang_code)
                                selected
                            @endif
                            >{{ $lang->lang_name }}</option>
                            @endif
                          @endforeach
                      </select>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-5 d-flex justify-content-end">
        </div>
      </div>
    </div>
  </div>
  <!-- Sweet Alert -->
  @if(session('success'))
  <script>swal("{{__('admin_blog_reference.success')}}", "{{__('admin_blog_reference.update_success_context')}}", "success");</script>
  @elseif(session('error'))
  <script>swal("{{__('admin_blog_reference.error')}}", "{{__('admin_blog_reference.update_error_context')}}", "error");</script>
  @endif
  <!-- Container-fluid starts-->
  <div class="container-fluid">
    <div class="card">
      <div class="card-body">
        <form class="theme-form needs-validation" novalidate="" action="{{ route('admin.app-reference-settings.update',[$content->id]) }}" method="POST">
          @method('PUT')
          @csrf
          <input type="hidden" name="lang_code" value="{{  request('lang_code') }}">
          <div class="mb-3">
            <label class="col-form-label p-0" for="title">{{__('admin_blog_reference.title')}}</label><span class="text-danger">*</span>
            <input class="form-control" id="title" name="title" type="text" placeholder="{{__('admin_blog_reference.title_placeholder')}}" value="{{ $content->title }}" required="">
            <div class="valid-feedback">{{__('admin_blog_reference.looks_good')}}</div>
          </div>
          <div class="mb-3">
            <label class="col-form-label p-0" for="subtitle">{{__('admin_blog_reference.subtitle')}}</label>
            <input class="form-control" id="subtitle" name="subtitle" type="text" placeholder="{{__('admin_blog_reference.subtitle_placeholder')}}" value="{{ $content->subtitle }}">
          </div>
          <div class="mb-3">
            <label class="col-form-label p-0" for="description">{{__('admin_blog_reference.description')}}</label>
            <textarea class="form-control" id="description" name="description" rows="12" placeholder="{{__('admin_blog_reference.description_placeholder')}}">{{ $content->description }}</textarea>
          </div>
          <div class="mb-3">
            <label class="col-form-label p-0" for="short_description">{{__('admin_blog_reference.short_description')}}</label>
            <textarea class="form-control" id="short_description" name="short_description" rows="6" placeholder="{{__('admin_blog_reference.short_description_placeholder')}}">{{ $content->short_description }}</textarea>
          </div>
          <div class="mb-3">
            <label class="col-form-label p-0" for="reference_count">{{__('admin_blog_reference.reference_limit')}}</label>
            <input class="form-control" id="reference_count" name="reference_count" type="number" min="1" max="9" placeholder="{{__('admin_blog_reference.reference_limit_placeholder')}}" value="{{ $content->reference_count }}">
          </div>
          <div class="mb-5">
            <div class="form-check form-switch">
              <input class="form-check-input" type="checkbox" name="is_active" id="is_active" @if ($content->is_active) checked @endif style="width: 40px; height: 20px;">
              <label class="form-check-label" for="is_active" style="margin-left:8px;">{{__('admin_blog_reference.is_active')}}</label>
            </div>
          </div>
          <div class="my-4">
            <div class="row">
              <div class="col-md-6 col-lg-8 col-xl-8 col-xxl-9"></div>
              <div class="col-md-6 col-lg-4 col-xl-4 col-xxl-3">
                <div class="input-group" >
                  <input style="width:100%;" type="submit" class="btn btn-primary" id="updateappreference" data-toggle="click-ripple" name="updateappreference" value="{{__('admin_blog_reference.update')}}">
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






