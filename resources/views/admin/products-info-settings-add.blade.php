@extends('admin.layouts.base')

@section('title',__('admin_products_page.create_content'))

@section('content')
<!-- Main Container -->
<div class="page-body pt-5">
  <div class="container-fluid">
    <div class="page-header">
      <div class="row">
        <div class="col-sm-7">
          <h3>{{__('admin_products_page.create_content')}} @if(count($langs) != 1)<span class="title-lang-code">({{ request('lang_code') }})</span>@endif</h3>
          <ol class="breadcrumb">
            <li class="breadcrumb-item disappear-500"><a href="{{ route('admin.index') }}"><ins> {{__('admin_products_page.main_page')}}</ins></a></li>
            <li class="breadcrumb-item disappear-500">{{__('admin_products_page.products')}}</li>
            <li class="breadcrumb-item disappear-500">{{__('admin_products_page.create_content')}}</li>
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
        <script>swal("{{__('admin_products_page.error')}}", "{{__('admin_products_page.create_error_context')}}", "error");</script>
      </div>
    @enderror
  @else
    @if(session('success'))
    <script>swal("{{__('admin_products_page.success')}}", "{{__('admin_products_page.create_success_context')}}", "success");</script>
    @elseif(session('error'))
    <script>swal("{{__('admin_products_page.error')}}", "{{__('admin_products_page.create_error_context')}}", "error");</script>
    @elseif(session('no_products_page_info'))
    <script>swal("{{__('admin_products_page.error')}}", "{{__('admin_products_page.create_error_no_info')}}", "error");</script>
    @endif
  @endif
  <!-- Container-fluid starts-->
  <div class="container-fluid">
    <div class="card">
      <div class="card-body">
        <form class="theme-form needs-validation" novalidate="" action="{{ route('admin.products-info-settings.store') }}" method="POST">
          @csrf
          <input type="hidden" name="lang_code" value="{{  request('lang_code') }}">
          <div class="mb-3">
            <label class="col-form-label p-0" for="title">{{__('admin_products_page.title')}}</label><span class="text-danger">*</span>
            <input class="form-control" id="title" name="title" type="text" placeholder="{{__('admin_products_page.title_placeholder')}}" value="{{ old('title') }}" required="">
            <div class="valid-feedback">{{__('admin_products_page.looks_good')}}</div>
          </div>
          <div class="mb-3">
            <label class="col-form-label p-0" for="subtitle">{{__('admin_products_page.subtitle')}}</label>
            <input class="form-control" id="subtitle" name="subtitle" type="text" placeholder="{{__('admin_products_page.subtitle_placeholder')}}" value="{{ old('subtitle') }}">
          </div>
          <div class="mb-3">
            <label class="col-form-label p-0" for="menu_name">{{__('admin_products_page.menu_name')}}</label><span class="text-danger">*</span>
            <input class="form-control" id="menu_name" name="menu_name" type="text" placeholder="{{__('admin_products_page.menu_name_placeholder')}}" value="{{ old('menu_name') }}" required="">
            <div class="valid-feedback">{{__('admin_products_page.looks_good')}}</div>
          </div>
          <div class="mb-3">
            <label class="col-form-label p-0" for="page_limit">{{__('admin_products_page.page_product_limit')}}</label>
            <input class="form-control" id="page_limit" name="page_limit" type="number" placeholder="{{__('admin_products_page.page_product_limit_placeholder')}}" value="{{ old('page_limit') }}">
          </div>
          <div class="mb-3">
            <label class="col-form-label p-0" for="video_embed_code">{{__('admin_products_page.youtube_code')}}</label>
            <input class="form-control" id="video_embed_code" name="video_embed_code" type="text" placeholder="{{__('admin_products_page.youtube_code_placeholder')}}" value="{{ old('video_embed_code') }}">
          </div>
          <div class="mb-4">
            <label class="form-label p-0" for="keywords">{{__('admin_products_page.keywords')}}</label>
            <textarea class="form-control" id="keywords" name="keywords" rows="4" placeholder="{{__('admin_products_page.keywords_placeholder')}}">{{ old('keywords') }}</textarea>
          </div>
          <div class="mb-4">
            <label class="form-label p-0" for="description">{{__('admin_products_page.description')}}</label>
            <textarea class="form-control" id="description" name="description" rows="8" placeholder="{{__('admin_products_page.description_placeholder')}}">{{ old('description') }}</textarea>
          </div>
          <div class="mb-4">
            <label class="form-label p-0" for="short_description">{{__('admin_products_page.short_description')}}</label>
            <textarea class="form-control" id="short_description" name="short_description" rows="6" placeholder="{{__('admin_products_page.short_description_placeholder')}}">{{ old('short_description') }}</textarea>
          </div>
          <div class="my-4">
            <div class="row">
              <div class="col-md-6 col-lg-8 col-xl-8 col-xxl-9"></div>
              <div class="col-md-6 col-lg-4 col-xl-4 col-xxl-3">
                <div class="input-group" >
                  <input style="width:100%;" type="submit" class="btn btn-primary" id="addproductinfosettings" data-toggle="click-ripple" name="addproductinfosettings" value="{{__('admin_products_page.save')}}">
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
