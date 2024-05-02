@extends('admin.layouts.base')

@section('title',__('admin_footer.create_content'))

@section('content')
<!-- Main Container -->
<div class="page-body pt-5">
  <div class="container-fluid">
    <div class="page-header">
      <div class="row">
        <div class="col-7">
          <h3>{{__('admin_footer.create_content')}} @if(count($langs) != 1)<span class="title-lang-code">({{ request('lang_code') }})</span>@endif</h3>
          <ol class="breadcrumb">
            <li class="breadcrumb-item disappear-500"><a href="{{ route('admin.index') }}"><ins> {{__('admin_footer.main_page')}}</ins></a></li>
            <li class="breadcrumb-item disappear-500">{{__('admin_footer.footer')}}</li>
            <li class="breadcrumb-item disappear-500">{{__('admin_footer.create_content')}}</li>
          </ol>
        </div>
        <div class="col-5 d-flex justify-content-end">
        </div>
      </div>
    </div>
  </div>
  <!-- Sweet Alert -->
  @if ($errors->any())
    @error('lang_code')
      <div>
        <script>swal("{{__('admin_footer.error')}}", "{{__('admin_footer.create_error_context')}}", "error");</script>
      </div>
    @enderror
  @else
    @if(session('success'))
    <script>swal("{{__('admin_footer.success')}}", "{{__('admin_footer.create_success_context')}}", "success");</script>
    @elseif(session('error'))
    <script>swal("{{__('admin_footer.error')}}", "{{__('admin_footer.create_error_context')}}", "error");</script>
    @endif
  @endif
  <!-- Container-fluid starts-->
  <div class="container-fluid">
    <div class="card">
      <div class="card-body">
        <form class="theme-form needs-validation" novalidate="" action="{{ route('admin.footer-settings.store') }}" method="POST">
          @csrf
          <input type="hidden" name="lang_code" value="{{  request('lang_code') }}">
          <div class="mb-3">
            <label class="col-form-label p-0" for="title">{{__('admin_footer.title')}}</label><span class="text-danger">*</span>
            <input class="form-control" id="title" name="title" type="text" placeholder="{{__('admin_footer.title_placeholder')}}" value="{{ old('title') }}" required="">
            <div class="valid-feedback">{{__('admin_footer.looks_good')}}</div>
          </div>
          <div class="mb-3">
            <label class="col-form-label p-0" for="description">{{__('admin_footer.description')}}</label>
            <textarea class="form-control" id="description" name="description" rows="12" placeholder="{{__('admin_footer.description_placeholder')}}">{{ old('description') }}</textarea>
          </div>
          <div class="mb-3">
            <label class="col-form-label p-0" for="copy_right">{{__('admin_footer.copyright')}}</label>
            <input class="form-control" id="copy_right" name="copy_right" type="text" placeholder="{{__('admin_footer.copyright_placeholder')}}" value="{{ old('copy_right') }}">
          </div>
          <div class="my-4">
            <div class="row">
              <div class="col-md-6 col-lg-8 col-xl-8 col-xxl-9"></div>
              <div class="col-md-6 col-lg-4 col-xl-4 col-xxl-3">
                <div class="input-group" >
                  <input style="width:100%;" type="submit" class="btn btn-primary" id="addfooter" data-toggle="click-ripple" name="addfooter" value="{{__('admin_footer.save')}}">
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






