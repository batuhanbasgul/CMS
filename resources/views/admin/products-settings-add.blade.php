@extends('admin.layouts.base')

@section('title',__('admin_product.create_content'))

@section('content')
<!-- Main Container -->
<div class="page-body pt-5">
  <div class="container-fluid">
    <div class="page-header">
      <div class="row">
        <div class="col-sm-7">
          <h3>{{__('admin_product.create_content')}} @if(count($langs) != 1)<span class="title-lang-code">({{ request('lang_code') }})</span>@endif</h3>
          <ol class="breadcrumb">
            <li class="breadcrumb-item disappear-500"><a href="{{ route('admin.index') }}"><ins>{{__('admin_product.main_page')}}</ins></a></li>
            <li class="breadcrumb-item disappear-500">{{__('admin_product.products')}}</li>
            <li class="breadcrumb-item disappear-500">{{__('admin_product.create_new_content')}}</li>
          </ol>
        </div>
        <div class="col-5 d-flex justify-content-end">
          <a class="mx-5" href="{{ route('admin.products-settings.index', ['lang_code' => request('lang_code')]) }}">
            <button type="button" class="btn btn-primary btn-sm m-1">
                <i class="me-2 fa-solid fa-angles-left"></i><span>{{__('admin_product.turn_back')}}</span>
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
        <script>swal("{{__('admin_product.error')}}", "{{__('admin_product.create_error_context')}}", "error");</script>
      </div>
    @enderror
  @else
    @if(session('success'))
    <script>swal("{{__('admin_product.success')}}", "{{__('admin_product.create_success_context')}}", "success");</script>
    @elseif(session('error'))
    <script>swal("{{__('admin_product.error')}}", "{{__('admin_product.create_error_context')}}", "error");</script>
    @endif
  @endif
  <!-- Container-fluid starts-->
  <div class="container-fluid">
    <div class="card">
      <div class="card-body">
        <form class="theme-form needs-validation" novalidate="" action="{{ route('admin.products-settings.store') }}" method="POST">
          @csrf
          <input type="hidden" name="lang_code" value="{{  request('lang_code') }}">
          <div class="mb-3">
            <label class="col-form-label p-0" for="name">{{__('admin_product.name')}}</label><span class="text-danger">*</span>
            <input class="form-control" id="name" name="name" type="text" placeholder="{{__('admin_product.name_placeholder')}}" value="{{ old('name') }}" required="">
            <div class="valid-feedback">{{__('admin_product.looks_good')}}</div>
          </div>
          <div class="mb-3">
            <label class="col-form-label p-0" for="product_no">{{__('admin_product.product_no')}}</label>
            <input class="form-control" id="product_no" name="product_no" type="text" placeholder="{{__('admin_product.product_no_placeholder')}}" value="{{ old('product_no') }}">
          </div>
          <div class="mb-3">
            <label class="col-form-label p-0" for="price">{{__('admin_product.product_price')}}</label>
            <input class="form-control" id="price" name="price" type="text" placeholder="{{__('admin_product.product_price_placeholder')}}" value="{{ old('price') }}">
          </div>
          <div class="mb-3">
            <label class="col-form-label p-0" for="product_url">{{__('admin_product.product_url')}}</label>
            <input class="form-control" id="product_url" name="product_url" type="text" placeholder="{{__('admin_product.product_url_placeholder')}}" value="{{ old('product_url') }}">
          </div>
          <div class="mb-3">
            <label class="col-form-label" for="product_category_uid">{{__('admin_product.product_categories')}}</label><span class="text-danger">*</span>
            <select class="js-example-basic-multiple col-sm-12" id="product_category_uid" name="product_category_uid[]" required multiple="multiple">
                @php $continue_first = true; @endphp
                <option value="{{ $categories[0]->uid }}" selected>{{ $categories[0]->name }}</option>
                @foreach ($categories as $category)
                @if($continue_first) @php $continue_first = false; @endphp @continue @endif
                <option value="{{ $category->uid }}">{{ $category->name }}</option>
                @endforeach
            </select>
          </div>
          <div class="mb-4">
            <label class="form-label p-0" for="keywords">{{__('admin_product.keywords')}}</label>
            <textarea class="form-control" id="keywords" name="keywords" rows="4" placeholder="{{__('admin_product.keywords_placeholder')}}">{{ old('keywords') }}</textarea>
          </div>
          <div class="mb-4">
            <label class="form-label p-0" for="description">{{__('admin_product.description')}}</label>
            <textarea class="form-control" id="description" name="description" rows="8" placeholder="{{__('admin_product.description_placeholder')}}">{{ old('description') }}</textarea>
          </div>
          <div class="my-4">
            <div class="row">
              <div class="col-md-6 col-lg-8 col-xl-8 col-xxl-9"></div>
              <div class="col-md-6 col-lg-4 col-xl-4 col-xxl-3">
                <div class="input-group" >
                  <input style="width:100%;" type="submit" class="btn btn-primary" id="addproduct" data-toggle="click-ripple" name="addproduct" value="{{__('admin_product.save')}}">
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






