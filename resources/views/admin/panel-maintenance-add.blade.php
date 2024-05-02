@extends('admin.layouts.base')

@section('title',__('admin_app_maintenance.create_content'))

@section('content')
<!-- Main Container -->
<div class="page-body pt-5">
  <div class="container-fluid">
    <div class="page-header">
      <div class="row">
        <div class="col-sm-7">
          <h3>{{__('admin_app_maintenance.create_content')}} @if(count($langs) != 1)<span class="title-lang-code">({{ request('lang_code') }})</span>@endif</h3>
          <ol class="breadcrumb">
            <li class="breadcrumb-item disappear-500"><a href="{{ route('admin.index') }}"><ins>{{__('admin_app_maintenance.main_page')}}</ins></a></li>
            <li class="breadcrumb-item disappear-500">{{__('admin_app_maintenance.main_page_maintenance')}}</li>
            <li class="breadcrumb-item disappear-500">{{__('admin_app_maintenance.create_content')}} </li>
          </ol>
        </div>
        <div class="col-sm-5">
        </div>
      </div>
    </div>
  </div>
  <!-- Sweet Alert -->
  @if ($errors->any())
    @error('lang_code')
      <div>
        <script>swal("{{__('admin_app_maintenance.error')}}", "{{__('admin_app_maintenance.create_error_context')}}", "error");</script>
      </div>
    @enderror
  @else
    @if(session('success'))
    <script>swal("{{__('admin_app_maintenance.success')}}", "{{__('admin_blog.create_success_context')}}", "success");</script>
    @elseif(session('error'))
    <script>swal("{{__('admin_app_maintenance.error')}}", "{{__('admin_app_maintenance.create_error_context')}}", "error");</script>
    @endif
  @endif
  <!-- Container-fluid starts-->
  <div class="container-fluid">
    <div class="card">
      <div class="card-body">
        <form class="theme-form needs-validation" novalidate="" action="{{ route('admin.panel-maintenance.store') }}" method="POST">
          @csrf
          <input type="hidden" name="lang_code" value="{{  request('lang_code') }}">
          <h4 class="border-bottom pb-2">{{__('admin_app_maintenance.content')}}</h4>
          <div class="mb-3">
            <label class="col-form-label p-0" for="title">{{__('admin_app_maintenance.title')}}</label><span class="text-danger">*</span>
            <input class="form-control" id="title" name="title" type="text" placeholder="{{__('admin_app_maintenance.title_placeholder')}}" value="{{ old('title') }}" required="">
            <div class="valid-feedback">{{__('admin_app_maintenance.looks_good')}}</div>
          </div>
          <div class="mb-3">
            <label class="col-form-label text-end p-0" for="start_date">{{__('admin_app_maintenance.date')}}</label>
            <input class="datepicker-here form-control digits" type="text" data-language="tr" id="start_date" name="start_date"
            placeholder="{{__('admin_app_maintenance.date_placeholder')}}" data-alt-input="true" data-date-format="dd MM yyyy" data-alt-format="j F Y">
          </div>
          <div class="mb-3">
            <label class="col-sm-3 col-form-label pt-0">{{__('admin_app_maintenance.color_code')}}</label>
            <div class="col-sm-9">
              <input class="form-control form-control-color" type="color" id="color" name="color" value="{{ old('color') }}" required="">
              <div class="valid-feedback">{{__('admin_app_maintenance.looks_good')}}</div>
            </div>
          </div>
          <div class="mb-3">
            <label class="col-form-label p-0" for="long_description">{{__('admin_app_maintenance.description')}}</label>
            <textarea class="form-control" id="long_description" name="long_description" rows="12" placeholder="{{__('admin_app_maintenance.description_placeholder')}}">{{ old('long_description') }}</textarea>
          </div>
          <div class="mb-3">
            <label class="col-form-label p-0" for="short_description">{{__('admin_app_maintenance.short_description')}}</label>
            <textarea class="form-control" id="short_description" name="short_description" rows="9" placeholder="{{__('admin_app_maintenance.short_description_placeholder')}}">{{ old('short_description') }}</textarea>
          </div>
          <div class="my-4">
            <div class="row">
              <div class="col-md-6 col-lg-8 col-xl-8 col-xxl-9"></div>
              <div class="col-md-6 col-lg-4 col-xl-4 col-xxl-3">
                <div class="input-group" >
                  <input style="width:100%;" type="submit" class="btn btn-primary" id="addpanelmaintenance" data-toggle="click-ripple" name="addpanelmaintenance" value="{{__('admin_app_maintenance.save')}}">
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
@endsection






