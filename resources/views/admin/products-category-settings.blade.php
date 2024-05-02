@extends('admin.layouts.base')

@section('title',__('admin_products_category.categories'))

@section('content')
<!-- Main Container -->
<div class="page-body pt-5">
  <div class="container-fluid">
    <div class="page-header">
      <div class="row">
        <div class="col-7">
            <div class="row">
                <div class="col-7">
                    <h3>{{__('admin_products_category.categories')}} @if(count($langs) != 1)<span class="title-lang-code">({{ request('lang_code') }})</span>@endif</h3>
                    <ol class="breadcrumb">
                      <li class="breadcrumb-item disappear-500"><a href="{{ route('admin.index') }}"><ins> {{__('admin_products_category.main_page')}}</ins></a></li>
                      <li class="breadcrumb-item disappear-500">{{__('admin_products_category.categories')}}</li>
                      <li class="breadcrumb-item disappear-500">{{__('admin_products_category.view_categories')}}</li>
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
                          <span class="disabled disappear-1250">({{__('admin_products_category.current_language')}})</span>
                          <img src="{{ asset($lang->icon) }}" alt="">
                        </div>
                        @endif
                      @endforeach
                      <select class="form-select" id="lang-select" name="lang-select" onchange="window.location.href=this.options[this.selectedIndex].value;">
                          @foreach ($langs as $lang)
                          @if ($lang->is_active)
                          <option value="{{ route('admin.products-category-settings.index',['lang_code'=>$lang->lang_code]) }}"
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
            Announcement's order.
          -->
        <a href="{{ route('admin.products-category-settings.edit-order', ['lang_code' => request('lang_code')]) }}">
          <button type="button" class="btn btn-primary btn-sm m-1">
            <i class="fas fa-stream me-2"></i><span class="disappear-800">{{__('admin_products_category.order_categories')}}</span>
          </button>
        </a>
          <!--
            Add new.
          -->
        <a href="{{ route('admin.products-category-settings.create',['lang_code'=>request('lang_code')]) }}">
          <button type="button" class="btn btn-primary btn-sm m-1">
            <i class="fa fa-fw fa-plus me-1"></i><span class="disappear-800">{{__('admin_products_category.add_new')}}</span>
          </button>
        </a>
        </div>
      </div>
    </div>
  </div>
<!-- Sweet Alert -->
@if(session('success'))
<script>swal("{{__('admin_products_category.success')}}", "{{__('admin_products_category.success_process')}}", "success");</script>
@elseif(session('error'))
<script>swal("{{__('admin_products_category.error')}}", "{{__('admin_products_category.error_process')}}", "error");</script>
@elseif (session('no_category'))
<script>swal("{{__('admin_products_category.error')}}", "{{__('admin_products_category.no_category')}}", "error")</script>
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
                          <th style="width: 5%;">{{__('admin_products_category.table_order')}}</th>
                          <th style="width: 20%;">{{__('admin_products_category.table_main_image')}}</th>
                          <th style="width: 20%;">{{__('admin_products_category.table_title')}}</th>
                          <th style="width: 10%;">{{__('admin_products_category.table_status')}}</th>
                          <th style="width: 20%;">{{__('admin_products_category.table_actions')}}</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($categories as $category)
                        <tr class="custom-table">
                          <td onclick="window.location='{{ route('admin.products-category-settings.edit', [$category->id, 'page' => 1, 'lang_code' => request('lang_code')]) }}';">
                            {{ $category->order }}
                          </td>
                          <td onclick="window.location='{{ route('admin.products-category-settings.edit', [$category->id, 'page' => 1, 'lang_code' => request('lang_code')]) }}';">
                            <img class="custom-table-image" src="
                            @if ($category->image_large_screen)
                            {{ asset($category->image_large_screen) }}
                            @elseif($category->image_small_screen)
                            {{ asset($category->image_small_screen) }}
                            @endif
                            " alt="">
                          </td>
                          <td onclick="window.location='{{ route('admin.products-category-settings.edit', [$category->id, 'page' => 1, 'lang_code' => request('lang_code')]) }}';">
                            {{ $category->name }}
                          </td>
                          <td onclick="window.location='{{ route('admin.products-category-settings.edit', [$category->id, 'page' => 1, 'lang_code' => request('lang_code')]) }}';">
                            @if ($category->is_active)
                            <div style="color:green">{{__('admin_products_category.table_active')}}</div>
                            @else
                            <div style="color:red">{{__('admin_products_category.passive')}}</div>
                            @endif
                          </td>
                          <td>
                            <div class="input-group">
                              <div class="col-6">
                                <a href="{{ route('admin.products-category-settings.edit',[$category->id]) }}">
                                  <button type="button" class="btn btn-sm btn-alt-primary mx-auto" style="width:90%">
                                    <i class="fas fa-pen me-1"></i><span class="disappear-1300"> {{__('admin_products_category.table_update')}}</span>
                                  </button>
                                </a>
                              </div>
                              <div class="col-6" onclick="return confirm('{{__('admin_products_category.delete_question')}}')">
                                <a href="{{ route('admin.products-category-settings.destroy',[$category->id]) }}">
                                  <button type="button" class="btn btn-sm btn-danger mx-auto" style="width:90%">
                                    <i class="fas fa-trash-alt me-1"></i><span class="disappear-1300">{{__('admin_products_category.table_delete')}}</span>
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
                            <th style="width: 5%;">{{__('admin_products_category.table_order')}}</th>
                            <th style="width: 20%;">{{__('admin_products_category.table_main_image')}}</th>
                            <th style="width: 20%;">{{__('admin_products_category.table_title')}}</th>
                            <th style="width: 10%;">{{__('admin_products_category.table_status')}}</th>
                            <th style="width: 20%;">{{__('admin_products_category.table_actions')}}</th>
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
