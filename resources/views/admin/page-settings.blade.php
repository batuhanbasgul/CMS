@extends('admin.layouts.base')

@section('title',__('admin_page_settings.pages'))

@section('content')
<!-- Main Container -->
<div class="page-body pt-5">
  <div class="container-fluid">
    <div class="page-header">
      <div class="row">
        <div class="col-7">
            <div class="row">
                <div class="col-7">
                    <h3>{{__('admin_page_settings.pages')}} @if(count($langs) != 1)<span class="title-lang-code">({{ request('lang_code') }})</span>@endif</h3>
                    <ol class="breadcrumb">
                      <li class="breadcrumb-item disappear-500"><a href="{{ route('admin.index') }}"><ins> {{__('admin_page_settings.main_page')}}</ins></a></li>
                      <li class="breadcrumb-item disappear-500">{{__('admin_page_settings.pages')}}</li>
                      <li class="breadcrumb-item disappear-500">{{__('admin_page_settings.view_pages')}}</li>
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
                          <span class="disabled disappear-1250">({{__('admin_page_settings.current_language')}})</span>
                          <img src="{{ asset($lang->icon) }}" alt="">
                        </div>
                        @endif
                      @endforeach
                      <select class="form-select" id="lang-select" name="lang-select" onchange="window.location.href=this.options[this.selectedIndex].value;">
                          @foreach ($langs as $lang)
                          @if ($lang->is_active)
                          <option value="{{ route('admin.page-settings.index',['lang_code'=>$lang->lang_code]) }}"
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
            Add new.
          -->
        <a href="{{ route('admin.page-settings.create', ['lang_code' => request('lang_code')]) }}">
          <button type="button" class="btn btn-primary btn-sm m-1">
            <i class="fa fa-fw fa-plus me-1"></i><span class="disappear-800">{{__('admin_page_settings.add_new')}}</span>
          </button>
        </a>
        </div>
      </div>
    </div>
  </div>
<!-- Sweet Alert -->
@if(session('success'))
<script>swal("{{__('admin_page_settings.success')}}", "{{__('admin_page_settings.success_process')}}", "success");</script>
@elseif(session('error'))
<script>swal("{{__('admin_page_settings.error')}}", "{{__('admin_page_settings.error_process')}}", "error");</script>
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
                          <th style="width: 25%;">{{__('admin_page_settings.table_image')}}</th>
                          <th style="width: 20%;">{{__('admin_page_settings.table_title')}}</th>
                          <th style="width: 15%;">{{__('admin_page_settings.table_status_desktop')}}</th>
                          <th style="width: 15%;">{{__('admin_page_settings.table_status_mobile')}}</th>
                          <th style="width: 25%;">{{__('admin_page_settings.table_actions')}}</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($pages as $page)
                        <tr class="custom-table">
                          <td onclick="window.location='{{ route('admin.page-settings.edit', [$page->id, 'page_number' => 1, 'lang_code' => request('lang_code')]) }}';">
                            <img src="
                            @if ($page->image_large_screen)
                            {{ asset($page->image_large_screen) }}
                            @elseif($page->image_small_screen)
                            {{ asset($page->image_small_screen) }}
                            @endif
                            " alt="" style="height: 64px">
                          </td>
                          <td onclick="window.location='{{ route('admin.page-settings.edit', [$page->id, 'page_number' => 1, 'lang_code' => request('lang_code')]) }}';">
                            {{ $page->title }}
                          </td>
                          <td onclick="window.location='{{ route('admin.page-settings.edit', [$page->id, 'page_number' => 1, 'lang_code' => request('lang_code')]) }}';">
                            @if ($page->is_desktop_active)
                            <div style="color:green">{{__('admin_page_settings.table_active')}}</div>
                            @else
                            <div style="color:red">{{__('admin_page_settings.table_passive')}}</div>
                            @endif
                          </td>
                          <td onclick="window.location='{{ route('admin.page-settings.edit', [$page->id, 'page_number' => 1, 'lang_code' => request('lang_code')]) }}';">
                            @if ($page->is_mobile_active)
                            <div style="color:green">{{__('admin_page_settings.table_active')}}</div>
                            @else
                            <div style="color:red">{{__('admin_page_settings.table_passive')}}</div>
                            @endif
                          </td>
                          <td>
                            <div class="input-group">
                              <div class="col-6">
                                <a href="{{ route('admin.page-settings.edit', [$page->id, 'page_number' => 1, 'lang_code' => request('lang_code')]) }}">
                                  <button type="button" class="btn btn-sm btn-alt-primary mx-auto" style="width:90%">
                                    <i class="fas fa-pen me-1"></i><span class="disappear-1300"> {{__('admin_page_settings.table_update')}}</span>
                                  </button>
                                </a>
                              </div>
                              <div class="col-6">
                                <form action="{{ route('admin.page-settings.destroy',[$page->id]) }}" method="POST" onclick="return confirm('{{__('admin_page_settings.delete_question')}}')">
                                  @csrf
                                  @method('DELETE')
                                  <button type="submit" class="btn btn-sm btn-danger mx-auto" style="width:90%">
                                    <i class="fas fa-trash-alt me-1"></i><span class="disappear-1300">{{__('admin_page_settings.delete')}}</span>
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
                            <th style="width: 25%;">{{__('admin_page_settings.table_image')}}</th>
                            <th style="width: 20%;">{{__('admin_page_settings.table_title')}}</th>
                            <th style="width: 15%;">{{__('admin_page_settings.table_status_desktop')}}</th>
                            <th style="width: 15%;">{{__('admin_page_settings.table_status_mobile')}}</th>
                            <th style="width: 25%;">{{__('admin_page_settings.table_actions')}}</th>
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
