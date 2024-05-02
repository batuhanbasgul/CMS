@extends('admin.layouts.base')

@section('title',__('admin_menu.menus'))

@section('content')
<!-- Main Container -->
<div class="page-body pt-5">
  <div class="container-fluid">
    <div class="page-header">
      <div class="row">
        <div class="col-10">
            <div class="row">
                <div class="col-6">
                    <h3>{{__('admin_menu.menus')}} @if(count($langs) != 1)<span class="title-lang-code">({{ request('lang_code') }})</span>@endif</h3>
                    <ol class="breadcrumb">
                      <li class="breadcrumb-item disappear-500"><a href="{{ route('admin.index') }}"><ins> {{__('admin_menu.main_page')}}</ins></a></li>
                      <li class="breadcrumb-item disappear-500">{{__('admin_menu.menus')}}</li>
                      <li class="breadcrumb-item disappear-500">{{__('admin_menu.view_menus')}}</li>
                    </ol>
                </div>
                <div class="col-3">
                    <!--
                      Refresh the page with selected lang_code.
                    -->
                    @if(count($langs) != 1)
                      @foreach ($langs as $lang)
                        @if ($lang_code==$lang->lang_code)
                        <div class="mx-2 d-none">
                          <span class="disabled">({{__('admin_menu.current_language')}})</span>
                          <img src="{{ asset($lang->icon) }}" alt="">
                        </div>
                        @endif
                      @endforeach
                      <select class="form-select" id="lang-select" name="lang-select" onchange="window.location.href=this.options[this.selectedIndex].value;">
                          @foreach ($langs as $lang)
                            @if ($lang->is_active)
                            <option value="{{ route('admin.menu-settings.index',['lang_code'=>$lang->lang_code]) }}"
                                @if ($lang_code==$lang->lang_code)
                                    selected
                                @endif
                            >{{ $lang->lang_name }}</option>
                            @endif
                          @endforeach
                      </select>
                    @endif
                </div>
                <div class="col-3">
                    <!--
                        Show only active or passives.
                    -->
                    <select class="form-select" id="lang-select" name="lang-select" onchange="window.location.href=this.options[this.selectedIndex].value;">
                      <option value="{{ route('admin.menu-settings.index',['lang_code'=>request('lang_code'),'show' => 'active']) }}"
                        @if (request('show') == 'active') selected @endif
                        >{{__('admin_menu.show_actives')}}</option>
                      <option value="{{ route('admin.menu-settings.index',['lang_code'=>request('lang_code'),'show' => 'passive']) }}"
                        @if (request('show') == 'passive') selected @endif
                        >{{__('admin_menu.show_passives')}}</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="col-2 d-flex justify-content-end">
            <!--
                App Cards order.
            -->
            <a href="{{ route('admin.menu-settings.edit-order', ['lang_code' => request('lang_code')]) }}">
                <button type="button" class="btn btn-primary btn-sm m-1">
                <i class="fas fa-stream me-2"></i><span class="disappear-800">{{__('admin_menu.update_order')}}</span>
                </button>
            </a>
        </div>
      </div>
    </div>
  </div>
<!-- Sweet Alert -->
@if(session('success'))
<script>swal("{{__('admin_menu.success')}}", "{{__('admin_menu.success_process')}}", "success");</script>
@elseif(session('error'))
<script>swal("{{__('admin_menu.error')}}", "{{__('admin_menu.error_process')}}", "error");</script>
@elseif (session('no_menu'))
<script>swal("{{__('admin_menu.error')}}", "{{__('admin_menu.no_menu')}}", "error")</script>
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
                          <th style="width: 5%;">{{__('admin_menu.table_order')}}</th>
                          <th style="width: 20%;">{{__('admin_menu.table_language')}}</th>
                          <th style="width: 20%;">{{__('admin_menu.table_menu_name')}}</th>
                          <th style="width: 15%;">{{__('admin_menu.table_status_desktop')}}</th>
                          <th style="width: 15%;">{{__('admin_menu.table_status_mobile')}}</th>
                          <th style="width: 15%;">{{__('admin_menu.table_actions')}}</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($menus as $menu)
                        <tr class="custom-table">
                          <td onclick="window.location='{{ route('admin.menu-settings.edit', [$menu->id, 'page' => 1, 'lang_code'=>request('lang_code'), 'show' => request('show')]) }}';">
                            {{ $menu->order }}
                          </td>
                          <td onclick="window.location='{{ route('admin.menu-settings.edit', [$menu->id, 'page' => 1, 'lang_code'=>request('lang_code'), 'show' => request('show')]) }}';">
                            {{ $menu->lang_code }}
                          </td>
                          <td onclick="window.location='{{ route('admin.menu-settings.edit', [$menu->id, 'page' => 1, 'lang_code'=>request('lang_code'), 'show' => request('show')]) }}';">
                            <a href="{{ route('admin.menu-settings.edit', [$menu->id, 'page' => 1, 'lang_code'=>request('lang_code'), 'show' => request('show')]) }}">
                                {{ $menu->menu_name }}
                            </a>
                          </td>
                          <td onclick="window.location='{{ route('admin.menu-settings.edit', [$menu->id, 'page' => 1, 'lang_code'=>request('lang_code'), 'show' => request('show')]) }}';">
                            @if ($menu->is_desktop_active)
                            <div style="color:green">{{__('admin_menu.table_active')}}</div>
                            @else
                            <div style="color:red">{{__('admin_menu.table_passive')}}</div>
                            @endif
                          </td>
                          <td onclick="window.location='{{ route('admin.menu-settings.edit', [$menu->id, 'page' => 1, 'lang_code'=>request('lang_code'), 'show' => request('show')]) }}';">
                            @if ($menu->is_mobile_active)
                            <div style="color:green">{{__('admin_menu.table_active')}}</div>
                            @else
                            <div style="color:red">{{__('admin_menu.table_passive')}}</div>
                            @endif
                          </td>
                          <td>
                            <a href="{{ route('admin.menu-settings.edit',[$menu->id, 'lang_code'=>request('lang_code')]) }}">
                              <button type="button" class="btn btn-sm btn-alt-primary mx-auto" style="width:90%">
                                <i class="fas fa-pen me-1"></i><span class="disappear-1300"> {{__('admin_menu.table_update')}}</span>
                              </button>
                            </a>
                          </td>
                        </tr>
                        @endforeach
                      </tbody>
                      <tfoot>
                        <tr>
                            <th style="width: 5%;">{{__('admin_menu.table_order')}}</th>
                            <th style="width: 20%;">{{__('admin_menu.table_language')}}</th>
                            <th style="width: 20%;">{{__('admin_menu.table_menu_name')}}</th>
                            <th style="width: 15%;">{{__('admin_menu.table_status_desktop')}}</th>
                            <th style="width: 15%;">{{__('admin_menu.table_status_mobile')}}</th>
                            <th style="width: 15%;">{{__('admin_menu.table_actions')}}</th>
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
