@extends('admin.layouts.base')

@section('title',__('admin_products_category.create_content'))

@section('content')
<!-- Main Container -->
<div class="page-body pt-5">
  <div class="container-fluid">
    <div class="page-header">
      <div class="row">
        <div class="col-sm-7">
          <h3>{{__('admin_products_category.create_new_content')}} @if(count($langs) != 1)<span class="title-lang-code">({{ request('lang_code') }})</span>@endif</h3>
          <ol class="breadcrumb">
            <li class="breadcrumb-item disappear-500"><a href="{{ route('admin.index') }}"><ins> {{__('admin_products_category.main_page')}}</ins></a></li>
            <li class="breadcrumb-item disappear-500">{{__('admin_products_category.categories')}}</li>
            <li class="breadcrumb-item disappear-500">{{__('admin_products_category.create_content')}}</li>
          </ol>
        </div>
        <div class="col-5 d-flex justify-content-end">
          <a class="mx-5" href="{{ route('admin.products-category-settings.index',['lang_code'=>request('lang_code')]) }}">
            <button type="button" class="btn btn-primary btn-sm m-1">
                <i class="me-2 fa-solid fa-angles-left"></i><span> {{__('admin_products_category.turn_back')}}</span>
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
        <script>swal("{{__('admin_products_category.error')}}", "{{__('admin_products_category.create_error_context')}}", "error");</script>
      </div>
    @enderror
  @else
    @if(session('success'))
    <script>swal("{{__('admin_products_category.success')}}", "{{__('admin_products_category.create_success_context')}}", "success");</script>
    @elseif(session('error'))
    <script>swal("{{__('admin_products_category.error')}}", "{{__('admin_products_category.create_error_context')}}", "error");</script>
    @endif
  @endif
  <!-- Container-fluid starts-->
  <div class="container-fluid">
    <div class="card">
      <div class="card-body">
        <form class="theme-form needs-validation" novalidate="" action="{{ route('admin.products-category-settings.store') }}" method="POST">
          @csrf
          <input type="hidden" name="lang_code" value="{{  request('lang_code') }}">
          <div class="mb-3">
            <label class="col-form-label p-0" for="name">{{__('admin_products_category.category_name')}}</label><span class="text-danger">*</span>
            <input class="form-control" id="name" name="name" type="text" placeholder="{{__('admin_products_category.category_name_placeholder')}}" value="{{ old('name') }}" required="">
            <div class="valid-feedback">{{__('admin_products_category.looks_good')}}</div>
          </div>
          <div class="mb-3">
            <label class="col-form-label" for="parent_category_uid">{{__('admin_products_category.parent_category')}}</label><span class="text-danger">*</span>
            <select class="js-example-basic-single col-sm-12" id="parent_category_uid" name="parent_category_uid" required>
                <option value="0">{{ $mainCategory->title }}</option>
                @foreach ($categories as $category)
                <!--
                  Do not add less level than 3.
                -->
                  @if($category->rank < 3)
                  <option value="{{ $category->uid }}">{{ $category->name }}</option>
                  @endif
                @endforeach
            </select>
          </div>
          <div class="mb-4">
            <label class="form-label p-0" for="keywords">{{__('admin_products_category.keywords')}}</label>
            <textarea class="form-control" id="keywords" name="keywords" rows="4" placeholder="{{__('admin_products_category.keywords_placeholder')}}">{{ old('keywords') }}</textarea>
          </div>
          <div class="mb-4">
            <label class="form-label p-0" for="description">{{__('admin_products_category.description')}}</label>
            <textarea class="form-control" id="description" name="description" rows="8" placeholder="{{__('admin_products_category.description_placeholder')}}">{{ old('description') }}</textarea>
          </div>
          <div class="my-4">
            <div class="row">
              <div class="col-md-6 col-lg-8 col-xl-8 col-xxl-9"></div>
              <div class="col-md-6 col-lg-4 col-xl-4 col-xxl-3">
                <div class="input-group" >
                  <input style="width:100%;" type="submit" class="btn btn-primary" id="addproductcategory" data-toggle="click-ripple" name="addproductcategory" value="{{__('admin_products_category.save')}}">
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
@endsection






