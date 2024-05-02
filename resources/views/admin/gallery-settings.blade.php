@extends('admin.layouts.base')

@section('title',__('admin_gallery.images'))

@section('content')
<!-- Main Container -->
<div class="page-body pt-5">
  <div class="container-fluid">
    <div class="page-header">
      <div class="row">
        <div class="col-7">
          <h3>{{__('admin_gallery.images')}} @if(count($langs) != 1)<span class="title-lang-code">({{ request('lang_code') }})</span>@endif</h3>
          <ol class="breadcrumb">
            <li class="breadcrumb-item disappear-500"><a href="{{ route('admin.index') }}"><ins> {{__('admin_gallery.main_page')}}</ins></a></li>
            <li class="breadcrumb-item disappear-500">{{__('admin_gallery.images')}}</li>
            <li class="breadcrumb-item disappear-500">{{__('admin_gallery.show_images')}}</li>
          </ol>
        </div>
        <div class="col-5 d-flex justify-content-end">
          <!--
            App Cards order.
          -->
        <a href="{{ route('admin.gallery-settings.edit-order', ['lang_code' => request('lang_code'), 'menu_code' => request('menu_code')]) }}">
          <button type="button" class="btn btn-primary btn-sm m-1">
            <i class="fas fa-stream me-2"></i><span class="disappear-800">{{__('admin_gallery.order_images')}}</span>
          </button>
        </a>
          <!--
            Add new.
          -->
        <a href="{{ route('admin.gallery-settings.create', ['lang_code' => request('lang_code'), 'menu_code' => request('menu_code')]) }}">
          <button type="button" class="btn btn-primary btn-sm m-1">
            <i class="fa fa-fw fa-plus me-1"></i><span class="disappear-800">{{__('admin_gallery.add_new')}}</span>
          </button>
        </a>
        <!--
          Turn Back.
        -->
        <a href="
        @if (request('menu_code') == 'page')
        {{ route('admin.page-settings.index', ['lang_code' => request('lang_code')]) }}
        @elseif (request('menu_code') == 'about_us')
        {{ route('admin.about-us-settings.index', ['lang_code' => request('lang_code')]) }}
        @elseif (request('menu_code') == 'references')
        {{ route('admin.reference-info-settings.index', ['lang_code' => request('lang_code')]) }}
        @elseif (request('menu_code') == 'gallery_info')
        {{ route('admin.gallery-info-settings.index', ['lang_code' => request('lang_code')]) }}
        @elseif (request('menu_code') == 'products_info')
        {{ route('admin.products-info-settings.index', ['lang_code' => request('lang_code')]) }}
        @elseif (request('menu_code') == 'announcement')
        {{ route('admin.announcement-info-settings.index', ['lang_code' => request('lang_code')]) }}
        @elseif (request('menu_code') == 'contact')
        {{ route('admin.contact-settings.index', ['lang_code' => request('lang_code')]) }}
        @endif ">
          <button type="button" class="btn btn-primary btn-sm m-1">
            <i class="me-2 fa-solid fa-angles-left"></i><span class="disappear-800">{{__('admin_gallery.turn_back')}}</span>
          </button>
        </a>
        </div>
      </div>
    </div>
  </div>
<!-- Sweet Alert -->
@if(session('success'))
<script>swal("{{__('admin_gallery.success')}}", "{{__('admin_gallery.success_process')}}", "success");</script>
@elseif(session('error'))
<script>swal("{{__('admin_gallery.error')}}", "{{__('admin_gallery.error_process')}}", "error");</script>
@elseif (session('no_image'))
<script>swal("{{__('admin_gallery.error')}}", "{{__('admin_gallery.update_error_no_image')}}", "error")</script>
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
                          <th style="width: 5%;">{{__('admin_gallery.table_order')}}</th>
                          <th style="width: 20%;">{{__('admin_gallery.table_image')}}</th>
                          <th style="width: 20%;">{{__('admin_gallery.table_title')}}</th>
                          <th style="width: 10%;">{{__('admin_gallery.table_status')}}</th>
                          <th style="width: 25%;">{{__('admin_gallery.table_actions')}}</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($images as $image)
                        <tr class="custom-table">
                          <td onclick="window.location='{{ route('admin.gallery-settings.edit', [$image->id, 'page' => 1, 'lang_code' => $image->lang_code]) }}';">
                            {{ $image->order }}
                          </td>
                          <td onclick="window.location='{{ route('admin.gallery-settings.edit', [$image->id, 'page' => 1, 'lang_code' => $image->lang_code]) }}';">
                            <img src="{{ asset($image->image) }}" alt="" style="height: 64px">
                          </td>
                          <td onclick="window.location='{{ route('admin.gallery-settings.edit', [$image->id, 'page' => 1, 'lang_code' => $image->lang_code]) }}';">
                            <a href="{{ route('admin.gallery-settings.edit', [$image->id, 'page' => 1, 'lang_code' => $image->lang_code]) }}">
                              {{ $image->title }}
                            </a>
                          </td>
                          <td onclick="window.location='{{ route('admin.gallery-settings.edit', [$image->id, 'page' => 1, 'lang_code' => $image->lang_code]) }}';">
                            @if ($image->is_active)
                            <div style="color:green">{{__('admin_gallery.table_active')}}</div>
                            @else
                            <div style="color:red">{{__('admin_gallery.table_passive')}}</div>
                            @endif
                          </td>
                          <td>
                            <div class="input-group">
                              <div class="col-6">
                                <a href="{{ route('admin.gallery-settings.edit', [$image->id, 'page' => 1, 'lang_code' => $image->lang_code]) }}">
                                  <button type="button" class="btn btn-sm btn-alt-primary mx-auto" style="width:90%">
                                    <i class="fas fa-pen me-1"></i><span class="disappear-1300"> {{__('admin_gallery.table_update')}}</span>
                                  </button>
                                </a>
                              </div>
                              <div class="col-6">
                                <form action="{{ route('admin.gallery-settings.destroy',[$image->id]) }}" method="POST" onclick="return confirm('{{__('admin_gallery.delete_question')}}')">
                                  @csrf
                                  @method('DELETE')
                                  <button type="submit" class="btn btn-sm btn-danger mx-auto" style="width:90%">
                                    <i class="fas fa-trash-alt me-1"></i><span class="disappear-1300"> {{__('admin_gallery.table_delete')}}</span>
                                  </button>
                                </form>
                              </div>
                            </div>
                          </td>
                        </tr>
                        @endforeach
                      </tbody>
                      <tfoot>
                        <tr>
                            <th style="width: 5%;">{{__('admin_gallery.table_order')}}</th>
                            <th style="width: 20%;">{{__('admin_gallery.table_image')}}</th>
                            <th style="width: 20%;">{{__('admin_gallery.table_title')}}</th>
                            <th style="width: 10%;">{{__('admin_gallery.table_status')}}</th>
                            <th style="width: 25%;">{{__('admin_gallery.table_actions')}}</th>
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
