@extends('admin.layouts.base')

@section('title',__('admin_user.create_content'))

@section('content')
<!-- Main Container -->
<div class="page-body pt-5">
  <div class="container-fluid">
    <div class="page-header">
      <div class="row">
        <div class="col-sm-7">
          <h3>{{__('admin_user.add_new_user')}}</h3>
          <ol class="breadcrumb">
            <li class="breadcrumb-item disappear-500"><a href="{{ route('admin.index') }}"><ins> {{__('admin_user.main_page')}}</ins></a></li>
            <li class="breadcrumb-item disappear-500">{{__('admin_user.user')}}</li>
            <li class="breadcrumb-item disappear-500">{{__('admin_user.add_new_user')}}</li>
          </ol>
        </div>
        <div class="col-5 d-flex justify-content-end">
          <a class="mx-5" href="{{ route('admin.user-settings.index', ['lang_code' => request('lang_code')]) }}">
            <button type="button" class="btn btn-primary btn-sm m-1">
                <i class="me-2 fa-solid fa-angles-left"></i><span>{{__('admin_user.turn_back')}}</span>
            </button>
          </a>
        </div>
      </div>
    </div>
  </div>
  <!-- Sweet Alert -->
  @if(session('success'))
  <script>swal("{{__('admin_user.success')}}", "{{__('admin_user.create_success_context')}}", "success");</script>
  @elseif(session('error'))
  <script>swal("{{__('admin_user.error')}}", "{{__('admin_user.create_error_context')}}", "error");</script>
  @elseif(session('email'))
  <script>swal("{{__('admin_user.error')}}", "{{__('admin_user.email_in_use')}}", "error");</script>
  @elseif(session('password_error'))
  <script>swal("{{__('admin_user.error')}}", "{{__('admin_user.passwords_did_not_match')}}", "error");</script>
  @endif
  <!-- Container-fluid starts-->
  <div class="container-fluid">
    <div class="card">
      <div class="card-body">
        <form class="theme-form needs-validation" novalidate="" action="{{ route('admin.user-settings.store') }}" method="POST">
          @csrf
          <div class="mb-3">
            <label class="col-form-label p-0" for="name">{{__('admin_user.user_name')}}</label><span class="text-danger">*</span>
            <input class="form-control" id="name" name="name" type="text" placeholder="{{__('admin_user.user_name_placeholder')}}" value="{{ old('name') }}" required="">
            <div class="valid-feedback">{{__('admin_user.looks_good')}}</div>
          </div>
          <div class="mb-3">
            <label class="col-form-label p-0" for="user_title">{{__('admin_user.user_title')}}</label>
            <input class="form-control" id="name" name="user_title" type="text" placeholder="{{__('admin_user.user_title_placeholder')}}" value="{{ old('user_title') }}">
          </div>
          <div class="mb-3">
            <label class="col-form-label p-0" for="email">{{__('admin_user.email')}}</label><span class="text-danger">*</span>
            <input class="form-control" id="email" name="email" type="email" placeholder="{{__('admin_user.email_placeholder')}}" value="{{ old('email') }}" required="">
            <div class="valid-feedback">{{__('admin_user.looks_good')}}</div>
          </div>
          <div class="mb-3">
            <label class="col-form-label p-0" for="password">{{__('admin_user.password1')}}</label><span class="text-danger">*</span>
            <input class="form-control" id="password" name="password" type="password" placeholder="{{__('admin_user.password1_placeholder')}}" value="{{ old('password') }}" required="">
            <div class="valid-feedback">{{__('admin_user.looks_good')}}</div>
          </div>
          <div class="mb-3">
            <label class="col-form-label p-0" for="confirm_password">{{__('admin_user.password2')}}</label><span class="text-danger">*</span>
            <input class="form-control" id="confirm_password" name="confirm_password" type="password" placeholder="{{__('admin_user.password2_placeholder')}}" value="{{ old('confirm_password') }}" required="">
            <div class="valid-feedback">{{__('admin_user.looks_good')}}</div>
          </div>
          <div class="my-4">
            <div class="row">
              <div class="col-md-6 col-lg-8 col-xl-8 col-xxl-9"></div>
              <div class="col-md-6 col-lg-4 col-xl-4 col-xxl-3">
                <div class="input-group" >
                  <input style="width:100%;" type="submit" class="btn btn-primary" id="adduser" data-toggle="click-ripple" name="adduser" value="{{__('admin_user.save')}}">
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






