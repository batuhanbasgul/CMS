@extends('admin.layouts.base')

@section('title',__('admin_contact.create_content'))

@section('content')
<!-- Main Container -->
<div class="page-body pt-5">
  <div class="container-fluid">
    <div class="page-header">
      <div class="row">
        <div class="col-7">
          <h3>{{__('admin_contact.create_content')}} @if(count($langs) != 1)<span class="title-lang-code">({{ request('lang_code') }})</span>@endif</h3>
          <ol class="breadcrumb">
            <li class="breadcrumb-item disappear-500"><a href="{{ route('admin.index') }}"><ins> {{__('admin_contact.main_page')}}</ins></a></li>
            <li class="breadcrumb-item disappear-500">{{__('admin_contact.contact')}}</li>
            <li class="breadcrumb-item disappear-500">{{__('admin_contact.create_content')}}</li>
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
        <script>swal("{{__('admin_contact.error')}}", "{{__('admin_contact.create_error_context')}}", "error");</script>
      </div>
    @enderror
  @else
    @if(session('success'))
    <script>swal("{{__('admin_contact.success')}}", "{{__('admin_contact.create_success_context')}}", "success");</script>
    @elseif(session('error'))
    <script>swal("{{__('admin_contact.error')}}", "{{__('admin_contact.create_error_context')}}", "error");</script>
    @endif
  @endif
  <!-- Container-fluid starts-->
  <div class="container-fluid">
    <div class="card">
      <div class="card-body">
        <form class="theme-form needs-validation" novalidate="" action="{{ route('admin.contact-settings.store') }}" method="POST">
          @csrf
          <input type="hidden" name="lang_code" value="{{  request('lang_code') }}">
          <div class="mb-3">
            <label class="col-form-label p-0" for="title">{{__('admin_contact.title')}}</label><span class="text-danger">*</span>
            <input class="form-control" id="title" name="title" type="text" placeholder="{{__('admin_contact.title_placeholder')}}" value="{{ old('title') }}" required="">
            <div class="valid-feedback">{{__('admin_contact.looks_good')}}</div>
          </div>
          <div class="mb-3">
            <label class="col-form-label p-0" for="subtitle">{{__('admin_contact.subtitle')}}</label>
            <input class="form-control" id="subtitle" name="subtitle" type="text" placeholder="{{__('admin_contact.subtitle_placeholder')}}" value="{{ old('subtitle') }}">
          </div>
          <div class="mb-3">
            <label class="col-form-label p-0" for="menu_name">{{__('admin_contact.menu_name')}}</label><span class="text-danger">*</span>
            <input class="form-control" id="menu_name" name="menu_name" type="text" placeholder="{{__('admin_contact.menu_name_placeholder')}}" value="{{ old('menu_name') }}" required="">
            <div class="valid-feedback">{{__('admin_contact.looks_good')}}</div>
          </div>
          <div class="mb-3">
            <label class="col-form-label p-0" for="video_embed_code">{{__('admin_contact.youtube_code')}}</label>
            <input class="form-control" id="video_embed_code" name="video_embed_code" type="text" placeholder="{{__('admin_contact.youtube_code_placeholder')}}" value="{{ old('video_embed_code') }}">
          </div>
          <div class="mb-3">
            <label class="col-form-label p-0" for="keywords">{{__('admin_contact.keywords')}}</label>
            <textarea class="form-control" id="keywords" name="keywords" rows="4" placeholder="{{__('admin_contact.keywords_placeholder')}}">{{ old('keywords') }}</textarea>
          </div>
          <div class="mb-3">
            <label class="col-form-label p-0" for="description">{{__('admin_contact.description')}}</label>
            <textarea class="form-control" id="description" name="description" rows="12" placeholder="{{__('admin_contact.description_placeholder')}}">{{ old('description') }}</textarea>
          </div>
          <div class="mb-3">
            <label class="col-form-label p-0" for="short_description">{{__('admin_contact.short_description')}}</label>
            <textarea class="form-control" id="short_description" name="short_description" rows="6" placeholder="{{__('admin_contact.short_description_placeholder')}}">{{ old('short_description') }}</textarea>
          </div>
          <div class="mb-3">
            <label class="col-form-label p-0" for="email">{{__('admin_contact.email')}}</label>
            <input class="form-control" id="email" name="email" type="text" placeholder="{{__('admin_contact.email_placeholder')}}" value="{{ old('email') }}">
          </div>
          <div class="mb-3">
            <label class="col-form-label p-0" for="phone1">{{__('admin_contact.first_phone')}}</label>
            <input class="form-control" id="phone1" name="phone1" type="text" placeholder="{{__('admin_contact.first_phone_placeholder')}}" value="{{ old('phone1') }}">
          </div>
          <div class="mb-3">
            <label class="col-form-label p-0" for="phone2">{{__('admin_contact.second_phone')}}</label>
            <input class="form-control" id="phone2" name="phone2" type="text" placeholder="{{__('admin_contact.second_phone_placeholder')}}" value="{{ old('phone2') }}">
          </div>
          <div class="mb-3">
            <label class="col-form-label p-0" for="gsm1">{{__('admin_contact.first_gsm')}}</label>
            <input class="form-control" id="gsm1" name="gsm1" type="text" placeholder="{{__('admin_contact.first_gsm_placeholder')}}" value="{{ old('gsm1') }}">
          </div>
          <div class="mb-3">
            <label class="col-form-label p-0" for="gsm2">{{__('admin_contact.second_gsm')}}</label>
            <input class="form-control" id="gsm2" name="gsm2" type="text" placeholder="{{__('admin_contact.second_gsm_placeholder')}}" value="{{ old('gsm2') }}">
          </div>
          <div class="mb-3">
            <label class="col-form-label p-0" for="fax">{{__('admin_contact.fax')}}</label>
            <input class="form-control" id="fax" name="fax" type="text" placeholder="{{__('admin_contact.fax_placeholder')}}" value="{{ old('fax') }}">
          </div>
          <div class="mb-3">
            <label class="col-form-label p-0" for="city">{{__('admin_contact.city')}}</label>
            <input class="form-control" id="city" name="city" type="text" placeholder="{{__('admin_contact.city_placeholder')}}" value="{{ old('city') }}">
          </div>
          <div class="mb-3">
            <label class="col-form-label p-0" for="address">{{__('admin_contact.address')}}</label>
            <input class="form-control" id="address" name="address" type="text" placeholder="{{__('admin_contact.address_placeholder')}}" value="{{ old('address') }}">
          </div>
          <div class="mb-3">
            <label class="col-form-label p-0" for="google_maps">{{__('admin_contact.google_maps')}}</label>
            <input class="form-control" id="google_maps" name="google_maps" type="text" placeholder="{{__('admin_contact.google_maps_placeholder')}}" value="{{ old('google_maps') }}">
          </div>
          <div class="my-4">
            <div class="row">
              <div class="col-md-6 col-lg-8 col-xl-8 col-xxl-9"></div>
              <div class="col-md-6 col-lg-4 col-xl-4 col-xxl-3">
                <div class="input-group" >
                  <input style="width:100%;" type="submit" class="btn btn-primary" id="addcontact" data-toggle="click-ripple" name="addcontact" value="{{__('admin_contact.save')}}">
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
<script>
  ClassicEditor
      .create( document.querySelector( '#short_description' ) )
      .catch( error => {
          console.error( error );
      } );
</script>
@endsection






