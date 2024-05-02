@extends('admin.layouts.base')

@section('title',__('admin_maintenance.maintenance_settings'))

@section('content')
<!-- Main Container -->
<div class="page-body pt-5">
  <div class="container-fluid">
    <div class="page-header">
      <div class="row">
        <div class="col-sm-7">
          <h3>{{__('admin_maintenance.maintenance_settings')}} </h3>
          <ol class="breadcrumb">
            <li class="breadcrumb-item disappear-500"><a href="{{ route('admin.index') }}"><ins>{{__('admin_maintenance.main_page')}}</ins></a></li>
            <li class="breadcrumb-item disappear-500">{{__('admin_maintenance.settings')}}</li>
            <li class="breadcrumb-item disappear-500">{{__('admin_maintenance.change_maintenance_status')}}</li>
          </ol>
        </div>
        <div class="col-5 d-flex justify-content-end">
        </div>
      </div>
    </div>
  </div>
  <!-- Container-fluid starts-->
  <div class="container-fluid">
    <div class="card">
      <div class="card-body">
        <form class="theme-form needs-validation" novalidate="" action="{{ route('admin.settings.update', [$settings->id]) }}" method="POST">
          @csrf
          @method('PUT')
          <div>
            <div class="form-check form-switch my-0">
              <input class="form-check-input custom-switch" type="checkbox" name="maintenance_app" id="maintenance_app" @if ($settings->maintenance_app) checked @endif>
              <label class="form-check-label custom-switch-label" for="maintenance_app">{{__('admin_maintenance.website_maintenance')}}</label>
            </div>
          </div>
          @can('master')
          <div>
            <div class="form-check form-switch my-0">
              <input class="form-check-input custom-switch" type="checkbox" name="maintenance_panel" id="maintenance_panel" @if ($settings->maintenance_panel) checked @endif>
              <label class="form-check-label custom-switch-label" for="maintenance_panel">{{__('admin_maintenance.panel_maintenance')}}</label>
            </div>
          </div>
          @endcan
          <div class="my-4">
            <div class="row">
              <div class="col-md-6 col-lg-8 col-xl-8 col-xxl-9"></div>
              <div class="col-md-6 col-lg-4 col-xl-4 col-xxl-3">
                <div class="input-group" >
                  <input style="width:100%;" type="submit" class="btn btn-primary" id="updatemaintenance" data-toggle="click-ripple" name="updatemaintenance" value="{{__('admin_maintenance.apply')}}">
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






