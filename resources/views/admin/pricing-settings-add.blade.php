@extends('admin.layouts.base')

@section('title',__('admin_pricing.create_content'))

@section('content')
<!-- Main Container -->
<div class="page-body pt-5">
  <div class="container-fluid">
    <div class="page-header">
      <div class="row">
        <div class="col-sm-7">
          <h3>{{__('admin_pricing.create_new_content')}} @if(count($langs) != 1)<span class="title-lang-code">({{ request('lang_code') }})</span>@endif</h3>
          <ol class="breadcrumb">
            <li class="breadcrumb-item disappear-500"><a href="{{ route('admin.index') }}"><ins> {{__('admin_pricing.main_page')}}</ins></a></li>
            <li class="breadcrumb-item disappear-500">{{__('admin_pricing.prices')}}</li>
            <li class="breadcrumb-item disappear-500">{{__('admin_pricing.create_content')}}</li>
          </ol>
        </div>
        <div class="col-5 d-flex justify-content-end">
          <a class="mx-5" href="{{ route('admin.pricing-settings.index',['lang_code' => request('lang_code')]) }}">
            <button type="button" class="btn btn-primary btn-sm m-1">
                <i class="me-2 fa-solid fa-angles-left"></i><span> {{__('admin_pricing.turn_back')}}</span>
            </button>
          </a>
        </div>
      </div>
    </div>
  </div>
  <!-- Sweet Alert -->
  @if ($errors->any())
    @error('lang_code')
      <div>
        <script>swal("{{__('admin_pricing.error')}}", "{{__('admin_pricing.create_error_context')}}", "error");</script>
      </div>
    @enderror
  @else
    @if(session('success'))
    <script>swal("{{__('admin_pricing.success')}}", "{{__('admin_pricing.create_success_context')}}", "success");</script>
    @elseif(session('error'))
    <script>swal("{{__('admin_pricing.error')}}", "{{__('admin_pricing.create_error_context')}}", "error");</script>
    @endif
  @endif
  <!-- Container-fluid starts-->
  <div class="container-fluid">
    <div class="card">
      <div class="card-body">
        <form class="theme-form needs-validation" novalidate="" action="{{ route('admin.pricing-settings.store') }}" method="POST">
          @csrf
          <input type="hidden" name="lang_code" value="{{  request('lang_code') }}">
          <div class="mb-3">
            <label class="col-form-label p-0" for="title">{{__('admin_pricing.title')}}</label><span class="text-danger">*</span>
            <input class="form-control" id="title" name="title" type="text" placeholder="{{__('admin_pricing.title_placeholder')}}" value="{{ old('title') }}" required="">
            <div class="valid-feedback">{{__('admin_pricing.looks_good')}}</div>
          </div>
          <div class="mb-3">
            <label class="col-form-label p-0" for="subtitle">{{__('admin_pricing.subtitle')}}</label>
            <input class="form-control" id="subtitle" name="subtitle" type="text" placeholder="{{__('admin_pricing.subtitle_placeholder')}}" value="{{ old('subtitle') }}">
          </div>
          <div class="mb-3">
            <label class="col-form-label p-0" for="pricing_url">{{__('admin_pricing.price_url')}}</label>
            <input class="form-control" id="pricing_url" name="pricing_url" type="text" placeholder="{{__('admin_pricing.price_url_placeholder')}}" value="{{ old('pricing_url') }}">
          </div>
          <div class="mb-3">
            <label class="col-form-label p-0" for="video_embed_code">{{__('admin_pricing.youtube_code')}}</label>
            <input class="form-control" id="video_embed_code" name="video_embed_code" type="text" placeholder="{{__('admin_pricing.youtube_code_placeholder')}}" value="{{ old('video_embed_code') }}">
          </div>
          <div class="mb-4">
            <label class="form-label p-0" for="keywords">{{__('admin_pricing.keywords')}}</label>
            <textarea class="form-control" id="keywords" name="keywords" rows="4" placeholder="{{__('admin_pricing.keywords_placeholder')}}">{{ old('keywords') }}</textarea>
          </div>
          <div class="mb-4">
            <label class="form-label p-0" for="description">{{__('admin_pricing.description')}}</label>
            <textarea class="form-control" id="description" name="description" rows="8" placeholder="{{__('admin_pricing.description_placeholder')}}">{{ old('description') }}</textarea>
          </div>
          <div class="mb-3">
            <label class="col-form-label p-0" for="entry1">{{__('admin_pricing.entry1')}}</label>
            <input class="form-control" id="entry1" name="entry1" type="text" placeholder="{{__('admin_pricing.entry1_placeholder')}}" value="{{ old('entry1') }}">
          </div>
          <div class="mb-3">
            <label class="col-form-label p-0" for="entry2">{{__('admin_pricing.entry2')}}</label>
            <input class="form-control" id="entry2" name="entry2" type="text" placeholder="{{__('admin_pricing.entry2_placeholder')}}" value="{{ old('entry2') }}">
          </div>
          <div class="mb-3">
            <label class="col-form-label p-0" for="entry3">{{__('admin_pricing.entry3')}}</label>
            <input class="form-control" id="entry3" name="entry3" type="text" placeholder="{{__('admin_pricing.entry3_placeholder')}}" value="{{ old('entry3') }}">
          </div>
          <div class="mb-3">
            <label class="col-form-label p-0" for="entry4">{{__('admin_pricing.entry4')}}</label>
            <input class="form-control" id="entry4" name="entry4" type="text" placeholder="{{__('admin_pricing.entry4_placeholder')}}" value="{{ old('entry4') }}">
          </div>
          <div class="mb-3">
            <label class="col-form-label p-0" for="entry5">{{__('admin_pricing.entry5')}}</label>
            <input class="form-control" id="entry5" name="entry5" type="text" placeholder="{{__('admin_pricing.entry5_placeholder')}}" value="{{ old('entry5') }}">
          </div>
          <div class="mb-3">
            <label class="col-form-label p-0" for="entry6">{{__('admin_pricing.entry6')}}</label>
            <input class="form-control" id="entry6" name="entry6" type="text" placeholder="{{__('admin_pricing.entry6_placeholder')}}" value="{{ old('entry6') }}">
          </div>
          <div class="mb-3">
            <label class="col-form-label p-0" for="entry7">{{__('admin_pricing.entry7')}}</label>
            <input class="form-control" id="entry7" name="entry7" type="text" placeholder="{{__('admin_pricing.entry7_placeholder')}}" value="{{ old('entry7') }}">
          </div>
          <div class="mb-3">
            <label class="col-form-label p-0" for="entry8">{{__('admin_pricing.entry8')}}</label>
            <input class="form-control" id="entry8" name="entry8" type="text" placeholder="{{__('admin_pricing.entry8_placeholder')}}" value="{{ old('entry8') }}">
          </div>
          <div class="mb-3">
            <label class="col-form-label p-0" for="entry9">{{__('admin_pricing.entry9')}}</label>
            <input class="form-control" id="entry9" name="entry9" type="text" placeholder="{{__('admin_pricing.entry9_placeholder')}}" value="{{ old('entry9') }}">
          </div>
          <div class="mb-3">
            <label class="col-form-label p-0" for="entry10">{{__('admin_pricing.entry10')}}</label>
            <input class="form-control" id="entry10" name="entry10" type="text" placeholder="{{__('admin_pricing.entry10_placeholder')}}" value="{{ old('entry10') }}">
          </div>
          <div class="my-4">
            <div class="row">
              <div class="col-md-6 col-lg-8 col-xl-8 col-xxl-9"></div>
              <div class="col-md-6 col-lg-4 col-xl-4 col-xxl-3">
                <div class="input-group" >
                  <input style="width:100%;" type="submit" class="btn btn-primary" id="addpricing" data-toggle="click-ripple" name="addpricing" value="{{__('admin_pricing.save')}}">
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






