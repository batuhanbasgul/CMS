
@extends('admin.layouts.base')

@section('title',__('admin_product.update_product_order'))

@section('content')
  <!-- Main Container -->
  <div class="page-body pt-5">
    <div class="container-fluid">
      <div class="page-header">
        <div class="row">
          <div class="col-7">
            <div class="row">
                <div class="col-7">
                    <h3>{{__('admin_product.order_products')}}@if(count($langs) != 1)<span class="title-lang-code">({{ request('lang_code') }})</span>@endif</h3>
                    <ol class="breadcrumb">
                      <li class="breadcrumb-item disappear-500"><a href="{{ route('admin.index') }}"><ins>{{__('admin_product.main_page')}}</ins></a></li>
                      <li class="breadcrumb-item disappear-500">{{__('admin_product.products')}}</li>
                      <li class="breadcrumb-item disappear-500">{{__('admin_product.order_products')}}</li>
                    </ol>
                </div>
                <div class="col-5">
                    <!--
                      Refresh the page with selected lang_code.
                    -->
                    @if(count($langs) != 1)
                      @foreach ($langs as $lang)
                        @if (request('lang_code')==$lang->lang_code)
                        <div class="mx-2 d-none">
                          <span class="disabled">({{__('admin_product.current_language')}})</span>
                          <img src="{{ asset($lang->icon) }}" alt="">
                        </div>
                        @endif
                      @endforeach
                      <select class="form-select" id="lang-select" name="lang-select" onchange="window.location.href=this.options[this.selectedIndex].value;">
                          @foreach ($langs as $lang)
                          @if ($lang->is_active)
                          <option value="{{ route('admin.products-settings.index',['lang_code'=>$lang->lang_code, 'page'=>request('page')]) }}"
                            @if (request('lang_code')==$lang->lang_code)
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
            <a class="mx-5" href="{{ route('admin.products-settings.index', ['lang_code' => request('lang_code'), 'menu_code' => request('menu_code')]) }}">
              <button type="button" class="btn btn-primary btn-sm m-1">
                <i class="me-2 fa-solid fa-angles-left"></i><span> {{__('admin_product.turn_back')}}</span>
              </button>
            </a>
          </div>
        </div>
      </div>
    </div>
    <!-- Sweet Alert -->
    @if(session('success'))
    <script>swal("{{__('admin_product.success')}}", "{{__('admin_product.success_process')}}", "success");</script>
    @elseif(session('error'))
    <script>swal("{{__('admin_product.error')}}", "{{__('admin_product.error_process')}}", "error");</script>
    @endif
    <div class="container-fluid">
      <div class="card">
        <div class="card-body">
          <form class="js-validation" action="{{ route('admin.products-settings.update',[$products[0]->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <input type="hidden" id="col-count" value="4">
            <input type="hidden" name="lang_code" value="{{ request('lang_code') }}">
              <div id="container-h-flow" class="container container-h-flow-4-cols">
                @foreach ($products as $item)
                  @if(!$item->is_active)
                  <div class="item" style="background-image: url({{ asset('admin-asset/gray-scale.png') }}), url({{ asset($item->image_large_screen) }});">
                    <div class="d-flex row m-1">
                      <div class="col-7"></div>
                      <div class="col-2 px-2 ms-3">
                        <a class="btn btn-sm btn-primary px-2" href="{{ route('admin.products-settings.edit', [$item->id]) }}">
                          <i class="fa fa-pencil-alt"></i>
                        </a>
                      </div>
                      <div class="col-2" onclick="return confirm(
                        @if (count($langs) == 1)
                        '{{__('admin_product.delete_question')}}'
                        @else
                        '{{__('admin_product.delete_category_question')}}'
                        @endif
                        )">
                        <div class="col-2">
                          <a class="btn btn-sm btn-danger px-2" href="{{ route('admin.products-settings.destroy', $item->id) }}">
                            <i class="fa fa-trash-alt"></i>
                          </a>
                        </div>
                      </div>
                      <div class="col-1"></div>
                      <p class="item-passive">
                        {{__('admin_product.passive')}}
                      </p>
                    </div>
                  @else
                  <div class="item" style="background-image: url({{ asset($item->image_large_screen) }});">
                    <p class="item-title d-none">
                      {{ $item->title }}
                    </p>
                    <div class="d-flex row m-1">
                      <div class="col-7"></div>
                      <div class="col-2 px-2 ms-3">
                        <a class="btn btn-sm btn-primary px-2" href="{{ route('admin.products-settings.edit', [$item->id]) }}">
                          <i class="fa fa-pencil-alt"></i>
                        </a>
                      </div>
                      <div class="col-2" onclick="return confirm(
                        @if (count($langs) == 1)
                        '{{__('admin_product.delete_question')}}'
                        @else
                        '{{__('admin_product.delete_category_question')}}'
                        @endif
                        )">
                        <div class="col-2">
                          <a class="btn btn-sm btn-danger px-2" href="{{ route('admin.products-settings.destroy', $item->id) }}">
                            <i class="fa fa-trash-alt"></i>
                          </a>
                        </div>
                      </div>
                      <div class="col-1"></div>
                  </div>
                  @endif
                  <h3 style="color:rgb(100, 100, 100); font-weight: bold;">{{$item->name}}</h3>
                </div>
                @endforeach
              </div>
            <input type="hidden" name="result_h_flow" id="result-h-flow"></input>
            <div class="mb-4">
              <div class="row">
                <div class="col-md-6 col-lg-8 col-xl-8 col-xxl-9"></div>
                <div class="col-md-6 col-lg-4 col-xl-4 col-xxl-3">
                  <div class="input-group" >
                    <small class="font-weight-bold ms-2">{{__('admin_product.drag_to_order')}}</small>
                    <input style="width:100%;" type="submit" class="btn btn btn-primary my-2 mx-1" id="updateproductsorder" name="updateproductsorder" value="{{__('admin_product.save_order')}}">
                  </div>
                </div>
              </div>
            </div>
          </form>
      </div>
    </div>
  </div>
</div>
@endsection



