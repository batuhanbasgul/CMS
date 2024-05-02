@extends('admin.layouts.base')

@section('title',__('admin_product.products'))

@section('content')
<!-- Main Container -->
<div class="page-body pt-5">
  <div class="container-fluid">
    <div class="page-header">
      <div class="row">
        <div class="col-7">
            <div class="row">
                <div class="col-7">
                    <h3>{{__('admin_product.products')}} @if(count($langs) != 1)<span class="title-lang-code">({{ request('lang_code') }})</span>@endif</h3>
                    <ol class="breadcrumb">
                      <li class="breadcrumb-item disappear-500"><a href="{{ route('admin.index') }}"><ins> {{__('admin_product.main_page')}}</ins></a></li>
                      <li class="breadcrumb-item disappear-500">{{__('admin_product.products')}}</li>
                      <li class="breadcrumb-item disappear-500">{{__('admin_product.view_products')}}</li>
                    </ol>
                </div>
                <div class="col-5">
                    <!--
                      Refreshing page with selected language code.
                    -->
                    @if(count($langs) != 1)
                      @foreach ($langs as $lang)
                        @if ($lang_code==$lang->lang_code)
                        <div class="mx-2 d-none">
                          <span class="disabled disappear-1250">({{__('admin_product.current_language')}})</span>
                          <img src="{{ asset($lang->icon) }}" alt="">
                        </div>
                        @endif
                      @endforeach
                      <select class="form-select" id="lang-select" name="lang-select" onchange="window.location.href=this.options[this.selectedIndex].value;">
                          @foreach ($langs as $lang)
                          @if ($lang->is_active)
                          <option value="{{ route('admin.products-settings.index',['lang_code'=>$lang->lang_code]) }}"
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
          <!--
            Product's order.
          -->
        <a href="{{ route('admin.products-settings.edit-order', ['lang_code' => request('lang_code')]) }}">
          <button type="button" class="btn btn-primary btn-sm m-1">
            <i class="fas fa-stream me-2"></i><span class="disappear-800">{{__('admin_product.order_products')}}</span>
          </button>
        </a>
          <!--
            Add new.
          -->
        <a href="{{ route('admin.products-settings.create',['lang_code'=>request('lang_code')]) }}">
          <button type="button" class="btn btn-primary btn-sm m-1">
            <i class="fa fa-fw fa-plus me-1"></i><span class="disappear-800">{{__('admin_product.add_new')}}</span>
          </button>
        </a>
        </div>
      </div>
    </div>
  </div>
<!-- Sweet Alert -->
@if(session('success'))
<script>("{{__('admin_product.success')}}", "{{__('admin_product.success_process')}}", "success");</script>
@elseif(session('error'))
<script>swal("{{__('admin_product.error')}}", "{{__('admin_product.error_process')}}", "error");</script>
@elseif (session('no_product'))
<script>swal("{{__('admin_product.error')}}", "{{__('admin_product.no_product')}}", "error")</script>
@elseif (session('no_category'))
<script>swal("{{__('admin_product.error')}}", "{{__('admin_product.no_category')}}", "error")</script>
@endif
  <!-- Container-fluid starts-->
  <div class="container-fluid">
          <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-body">
                  <div class="dt-ext table-responsive p-2 mt-0">
                    <table class="display" id="responsive">
                      <thead>
                        <tr>
                          <th style="width: 5%;">{{__('admin_product.table_order')}}</th>
                          <th style="width: 20%;">{{__('admin_product.table_main_image')}}</th>
                          <th style="width: 20%;">{{__('admin_product.table_title')}}</th>
                          <th style="width: 10%;">{{__('admin_product.table_status')}}</th>
                          <th style="width: 20%;">{{__('admin_product.table_actions')}}</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($products as $product)
                        <tr class="custom-table">
                          <td onclick="window.location='{{ route('admin.products-settings.edit', [$product->id, 'page' => 1, 'lang_code' => request('lang_code')]) }}';">
                            {{ $product->order }}
                          </td>
                          <td onclick="window.location='{{ route('admin.products-settings.edit', [$product->id, 'page' => 1, 'lang_code' => request('lang_code')]) }}';">
                            <img class="custom-table-image" src="
                            @if ($product->image_large_screen)
                            {{ asset($product->image_large_screen) }}
                            @elseif($product->image_small_screen)
                            {{ asset($product->image_small_screen) }}
                            @endif
                            " alt="">
                          </td>
                          <td onclick="window.location='{{ route('admin.products-settings.edit', [$product->id, 'page' => 1, 'lang_code' => request('lang_code')]) }}';">
                            {{ $product->name }}
                          </td>
                          <td onclick="window.location='{{ route('admin.products-settings.edit', [$product->id, 'page' => 1, 'lang_code' => request('lang_code')]) }}';">
                            @if ($product->is_active)
                            <div style="color:green">{{__('admin_product.table_active')}}</div>
                            @else
                            <div style="color:red">{{__('admin_product.table_passive')}}</div>
                            @endif
                          </td>
                          <td>
                            <div class="input-group">
                              <div class="col-6">
                                <a href="{{ route('admin.products-settings.edit',[$product->id, 'lang_code' => request('lang_code')]) }}">
                                  <button type="button" class="btn btn-sm btn-alt-primary mx-auto" style="width:90%">
                                    <i class="fas fa-pen me-1"></i><span class="disappear-1300">{{__('admin_product.table_update')}}</span>
                                  </button>
                                </a>
                              </div>
                              <div class="col-6" onclick="return confirm(
                                @if (count($langs) == 1)
                                '{{__('admin_product.delete_question')}}'
                                @else
                                '{{__('admin_product.delete_category_question')}}'
                                @endif
                                )">
                                <a href="{{ route('admin.products-settings.destroy', $product->id) }}">
                                  <button type="button" class="btn btn-sm btn-danger mx-auto" style="width:90%">
                                    <i class="fas fa-trash-alt me-1"></i><span class="disappear-1300">{{__('admin_product.table_delete')}}</span>
                                  </button>
                                </a>
                              </div>
                            </div>
                          </td>
                        </tr>
                        @endforeach
                      </tbody>
                      <tfoot>
                        <tr>
                            <th style="width: 5%;">{{__('admin_product.table_order')}}</th>
                            <th style="width: 20%;">{{__('admin_product.table_main_image')}}</th>
                            <th style="width: 20%;">{{__('admin_product.table_title')}}</th>
                            <th style="width: 10%;">{{__('admin_product.table_status')}}</th>
                            <th style="width: 20%;">{{__('admin_product.table_actions')}}</th>
                        </tr>
                      </tfoot>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
  </div>
  <!-- Container-fluid Ends-->
</div>

@endsection
