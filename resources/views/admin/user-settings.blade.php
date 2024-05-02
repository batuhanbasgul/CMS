@extends('admin.layouts.base')

@section('title',__('admin_user.users'))

@section('content')
<!-- Main Container -->
<div class="page-body pt-5">
  <div class="container-fluid">
    <div class="page-header">
      <div class="row">
        <div class="col-7">
            <h3>{{__('admin_user.users')}} </h3>
            <ol class="breadcrumb">
              <li class="breadcrumb-item disappear-500"><a href="{{ route('admin.index') }}"><ins>{{__('admin_user.main_page')}}</ins></a></li>
              <li class="breadcrumb-item disappear-500">{{__('admin_user.users')}}</li>
              <li class="breadcrumb-item disappear-500">{{__('admin_user.view_users')}}</li>
            </ol>
        </div>
        <div class="col-5 d-flex justify-content-end">
          <!--
            Add new.
          -->
        <a href="{{ route('admin.user-settings.create') }}">
          <button type="button" class="btn btn-primary btn-sm m-1">
            <i class="fa fa-fw fa-plus me-1"></i><span class="disappear-800">{{__('admin_user.add_new')}}</span>
          </button>
        </a>
        </div>
      </div>
    </div>
  </div>
<!-- Sweet Alert -->
@if(session('success'))
<script>swal("{{__('admin_user.success')}}", "{{__('admin_user.success_process')}}", "success");</script>
@elseif(session('error'))
<script>swal("{{__('admin_user.error')}}", "{{__('admin_user.error_process')}}", "error");</script>
@elseif (session('own'))
<script>swal("{{__('admin_user.error')}}", "{{__('admin_user.cannot_delete_self')}}", "error")</script>
@elseif (session('role'))
<script>swal("{{__('admin_user.error')}}", "{{__('admin_user.cannot_delete_higher')}}", "error")</script>
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
                          <th style="width: 20%;">{{__('admin_user.table_user_name')}}</th>
                          <th style="width: 20%;">{{__('admin_user.table_user_title')}}</th>
                          <th style="width: 20%;">{{__('admin_user.table_email')}}</th>
                          <th style="width: 20%;">{{__('admin_user.table_status')}}</th>
                          <th style="width: 20%;">{{__('admin_user.table_actions')}}</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($users as $user)
                        <tr class="custom-table">
                          <td onclick="window.location=''{{ route('admin.user-settings.edit', [$user->id, 'page' => 1]) }}';">
                            {{ $user->name }}
                          </td>
                          <td onclick="window.location=''{{ route('admin.user-settings.edit', [$user->id, 'page' => 1]) }}';">
                            {{ $user->title }}
                          </td>
                          <td onclick="window.location=''{{ route('admin.user-settings.edit', [$user->id, 'page' => 1]) }}';">
                            {{ $user->email }}
                          </td>
                          <td onclick="window.location=''{{ route('admin.user-settings.edit', [$user->id, 'page' => 1]) }}';">
                            @if ($user->is_active)
                            <div style="color:green">{{__('admin_user.table_active')}}</div>
                            @else
                            <div style="color:red">{{__('admin_user.table_passive')}}</div>
                            @endif
                          </td>
                          <td>
                            <div class="input-group">
                              <div class="col-6">
                                <a href="{{ route('admin.user-settings.edit',[$user->id]) }}">
                                  <button type="button" class="btn btn-sm btn-alt-primary mx-auto" style="width:90%">
                                    <i class="fas fa-pen me-1"></i><span class="disappear-1300"> {{__('admin_user.table_order')}}</span>
                                  </button>
                                </a>
                              </div>
                              <div class="col-6" onclick="return confirm('{{__('admin_user.delete_question')}}')">
                                <a href="{{ route('admin.user-settings.destroy',[$user->id]) }}">
                                  <button type="button" class="btn btn-sm btn-danger mx-auto" style="width:90%">
                                    <i class="fas fa-trash-alt me-1"></i><span class="disappear-1300"> {{__('admin_user.table_delete')}}</span>
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
                            <th style="width: 20%;">{{__('admin_user.table_user_name')}}</th>
                            <th style="width: 20%;">{{__('admin_user.table_user_title')}}</th>
                            <th style="width: 20%;">{{__('admin_user.table_email')}}</th>
                            <th style="width: 20%;">{{__('admin_user.table_status')}}</th>
                            <th style="width: 20%;">{{__('admin_user.table_actions')}}</th>
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
