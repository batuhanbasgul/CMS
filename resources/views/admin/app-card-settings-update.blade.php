@extends('admin.layouts.base')

@section('title',__('admin_cards.update_content'))

@section('content')
<!-- Main Container -->
<div class="page-body pt-5">
  <div class="container-fluid">
    <div class="page-header">
      <div class="row">
        <div class="col-7">
          <h3>{{__('admin_cards.update_content')}} @if(count($langs) != 1)<span class="title-lang-code">({{ request('lang_code') }})</span>@endif</h3>
          <ol class="breadcrumb">
            <li class="breadcrumb-item disappear-500"><a href="{{ route('admin.index') }}"><ins>{{__('admin_cards.main_page')}}</ins></a></li>
            <li class="breadcrumb-item disappear-500">{{__('admin_cards.main_page_cards')}}</li>
            <li class="breadcrumb-item disappear-500">{{__('admin_cards.update_content')}}</li>
          </ol>
        </div>
        <div class="col-5 d-flex justify-content-end">
            <a href="{{ route('admin.app-card-settings.index', ['lang_code' => request('lang_code')]) }}">
              <button type="button" class="btn btn-primary btn-sm m-1">
                  <i class="me-2 fa-solid fa-angles-left"></i><span class="disappear-500">{{__('admin_cards.turn_back')}}</span>
              </button>
            </a>
        </div>
      </div>
    </div>
  </div>
  <!-- Sweet Alert -->
  @if(session('success'))
  <script>swal("{{__('admin_cards.success')}}", "{{__('admin_blog.update_success_context')}}", "success");</script>
  @elseif(session('error'))
  <script>swal("{{__('admin_cards.error')}}", "{{__('admin_cards.update_error_context')}}", "error");</script>
  @endif
  <!-- Container-fluid starts-->
  <div class="container-fluid">
    <div class="card">
      <div class="card-body">
        <form class="theme-form needs-validation" novalidate="" action="{{ route('admin.app-card-settings.update',[$card->id]) }}" method="POST">
          @csrf
          @method('PUT')
          <input type="hidden" name="lang_code" value="{{  request('lang_code') }}">
          <div class="mb-3">
            <label class="col-form-label p-0" for="title">{{__('admin_cards.title')}}</label>
            <input class="form-control" id="title" name="title" type="text" placeholder="{{__('admin_cards.title_placeholder')}}" value="{{ $card->title }}">
          </div>
          <div class="mb-3">
            <label class="col-form-label p-0" for="title">{{__('admin_cards.icon')}}</label>
            <input class="form-control" id="icon" name="icon" type="text" placeholder="{{__('admin_cards.icon_placeholder')}}" value="{{ $card->icon }}">
          </div>
          <div class="mb-3">
            <label class="col-form-label p-0" for="description">{{__('admin_cards.description')}}</label>
            <textarea class="form-control" id="description" name="description" rows="12" placeholder="{{__('admin_cards.description_placeholder')}}">{{ $card->description }}</textarea>
          </div>
          <div class="my-4">
            <div class="row">
              <div class="col-md-6 col-lg-8 col-xl-8 col-xxl-9"></div>
              <div class="col-md-6 col-lg-4 col-xl-4 col-xxl-3">
                <div class="input-group" >
                  <input style="width:100%;" type="submit" class="btn btn-primary" id="updatecardsettings" data-toggle="click-ripple" name="updatecardsettings" value="{{__('admin_cards.update')}}">
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






