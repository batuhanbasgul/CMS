@extends('admin.layouts.base')

@section('title',__('admin_lang.languages'))

@section('content')
<!-- Main Container -->
<div class="page-body pt-5">
  <div class="container-fluid">
    <div class="page-header">
      <div class="row">
        <div class="col-7">
          <h3>{{__('admin_lang.languages')}} </h3>
          <ol class="breadcrumb">
            <li class="breadcrumb-item disappear-500"><a href="{{ route('admin.index') }}"><ins> {{__('admin_lang.main_page')}}</ins></a></li>
            <li class="breadcrumb-item disappear-500">{{__('admin_lang.languages')}}</li>
            <li class="breadcrumb-item disappear-500">{{__('admin_lang.view_languages')}}</li>
          </ol>
        </div>
        <div class="col-5 d-flex justify-content-end">
          <!--
            App Langs order.
          -->
        <a href="{{ route('admin.lang-settings.edit-order') }}">
          <button type="button" class="btn btn-primary btn-sm m-1">
            <i class="fas fa-stream me-2"></i><span class="disappear-800">{{__('admin_lang.update_language_order')}}</span>
          </button>
        </a>
          <!--
            Add new.
          -->
        <a href="{{ route('admin.lang-settings.create') }}">
          <button type="button" class="btn btn-primary btn-sm m-1">
            <i class="fa fa-fw fa-plus me-1"></i><span class="disappear-800">{{__('admin_lang.add_new')}}</span>
          </button>
        </a>
        </div>
      </div>
    </div>
  </div>
<!-- Sweet Alert -->
@if(session('success'))
<script>swal("{{__('admin_lang.success')}}", "{{__('admin_lang.success_process')}}", "success");</script>
@elseif(session('error'))
<script>swal("{{__('admin_lang.error')}}", "{{__('admin_lang.error_process')}}", "error");</script>
@elseif (session('no_lang'))
<script>swal("{{__('admin_lang.error')}}", "{{__('admin_lang.no_language')}}", "error")</script>
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
                          <th style="width: 5%;">{{__('admin_lang.table_order')}}</th>
                          <th style="width: 20%;">{{__('admin_lang.table_lang_code')}}</th>
                          <th style="width: 20%;">{{__('admin_lang.table_lang_name')}}</th>
                          <th style="width: 20%;">{{__('admin_lang.table_default')}}</th>
                          <th style="width: 20%;">{{__('admin_lang.table_status')}}</th>
                          <th style="width: 15%;">{{__('admin_lang.table_actions')}}</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($langs as $lang)
                        <tr class="custom-table">
                            <td onclick="window.location='{{ route('admin.lang-settings.edit', [$lang->id, 'page' => 1]) }}';">
                                {{ $lang->order }}
                            </td>
                            <td onclick="window.location='{{ route('admin.lang-settings.edit', [$lang->id, 'page' => 1]) }}';">
                              {{ $lang->lang_code }}
                            </td>
                            <td onclick="window.location='{{ route('admin.lang-settings.edit', [$lang->id, 'page' => 1]) }}';">
                              {{ $lang->lang_name }}
                            </td>
                            <td onclick="window.location='{{ route('admin.lang-settings.edit', [$lang->id, 'page' => 1]) }}';">
                              @if ($lang->is_default)
                              <div style="color:green">{{__('admin_lang.table_default')}}</div>
                              @else
                              <div style="color:red">{{__('admin_lang.table_not_default')}}</div>
                              @endif
                            </td>
                            <td onclick="window.location='{{ route('admin.lang-settings.edit', [$lang->id, 'page' => 1]) }}';">
                              @if ($lang->is_active)
                              <div style="color:green">{{__('admin_lang.table_active')}}</div>
                              @else
                              <div style="color:red">{{__('admin_lang.table_passive')}}</div>
                              @endif
                            </td>
                          <td>
                            <div class="input-group">
                              <div class="col-12">
                                <a href="{{ route('admin.lang-settings.edit',[$lang->id]) }}">
                                  <button type="button" class="btn btn-sm btn-alt-primary mx-auto" style="width:90%">
                                    <i class="fas fa-pen me-1"></i><span class="disappear-1300"> {{__('admin_lang.table_update')}}</span>
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
                            <th style="width: 5%;">{{__('admin_lang.table_order')}}</th>
                            <th style="width: 20%;">{{__('admin_lang.table_lang_code')}}</th>
                            <th style="width: 20%;">{{__('admin_lang.table_lang_name')}}</th>
                            <th style="width: 20%;">{{__('admin_lang.table_default')}}</th>
                            <th style="width: 20%;">{{__('admin_lang.table_status')}}</th>
                            <th style="width: 15%;">{{__('admin_lang.table_actions')}}</th>
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
