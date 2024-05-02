
@extends('admin.layouts.base')

@section('title',__('admin_gallery.update_image_order'))

@section('content')
  <!-- Main Container -->
  <div class="page-body pt-5">
    <div class="container-fluid">
      <div class="page-header">
        <div class="row">
          <div class="col-sm-7">
            <h3>{{__('admin_gallery.update_image_order')}} @if(count($langs) != 1)<span class="title-lang-code">({{ request('lang_code') }})</span>@endif</h3>
            <ol class="breadcrumb">
              <li class="breadcrumb-item disappear-500"><a href="{{ route('admin.index') }}"><ins>{{__('admin_gallery.main_page')}}</ins></a></li>
              <li class="breadcrumb-item disappear-500">{{__('admin_gallery.gallery_images')}}</li>
              <li class="breadcrumb-item disappear-500">{{__('admin_gallery.update_image_order')}}</li>
            </ol>
          </div>
          <div class="col-5 d-flex justify-content-end">
            <a href="{{ route('admin.gallery-settings.index',['lang_code'=> request('lang_code') , 'menu_code' => request('menu_code')]) }}">
              <button type="button" class="btn btn-primary btn-sm m-1">
                <i class="me-2 fa-solid fa-angles-left"></i><span> {{__('admin_gallery.turn_back')}}</span>
              </button>
            </a>
          </div>
        </div>
      </div>
    </div>
    <!-- Sweet Alert -->
    @if(session('success'))
    <script>swal("{{__('admin_gallery.success')}}", "{{__('admin_gallery.success_process')}}", "success");</script>
    @elseif(session('error'))
    <script>swal("{{__('admin_gallery.error')}}", "{{__('admin_gallery.error_process')}}", "error");</script>
    @endif
    <div class="container-fluid">
      <div class="card">
        <div class="card-body">
          <form class="js-validation" action="{{ route('admin.gallery-settings.update',[$galleries[0]->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <input type="hidden" id="col-count" value="4">
            <input type="hidden" name="menu_code" value="{{ request('menu_code') }}">
            <input type="hidden" name="lang_code" value="{{ request('lang_code') }}">
              <div id="container-h-flow" class="container container-h-flow-4-cols">
                @foreach ($galleries as $item)
                  @if(!$item->is_active)
                  <div class="item" style="background-image: url({{ asset('admin-asset/gray-scale.png') }}), url({{ asset($item->image) }});">
                    <div class="d-flex row m-1">
                      <div class="col-7"></div>
                      <div class="col-2 px-2 ms-3">
                      </div>
                      <div class="col-2">
                        <a class="btn btn-sm btn-primary px-2" href="{{ route('admin.gallery-settings.edit', [$item->id]) }}">
                          <i class="fa fa-pencil-alt"></i>
                        </a>
                      </div>
                      <div class="col-1"></div>
                    </div>
                  @else
                  <div class="item" style="background-image: url({{ asset($item->image) }});">
                    <p class="item-title d-none">
                      {{ $item->title }}
                    </p>
                    <div class="d-flex row m-1">
                      <div class="col-7"></div>
                      <div class="col-2 px-2 ms-3">
                      </div>
                      <div class="col-2">
                        <a class="btn btn-sm btn-primary px-2" href="{{ route('admin.gallery-settings.edit', [$item->id]) }}">
                          <i class="fa fa-pencil-alt"></i>
                        </a>
                      </div>
                      <div class="col-1"></div>
                    </div>
                  @endif
                </div>
                @endforeach
              </div>
            <input type="hidden" name="result_h_flow" id="result-h-flow"></input>
            <div class="mb-4">
              <div class="row">
                <div class="col-md-6 col-lg-8 col-xl-8 col-xxl-9"></div>
                <div class="col-md-6 col-lg-4 col-xl-4 col-xxl-3">
                  <div class="input-group" >
                    <small class="font-weight-bold ms-2">{{__('admin_gallery.drag_to_order')}}</small>
                    <input style="width:100%;" type="submit" class="btn btn btn-primary my-2 mx-1" id="updategalleryorder" name="updategalleryorder" value="{{__('admin_gallery.save_order')}}">
                  </div>
                </div>
              </div>
            </div>
          </form>
      </div>
    </div>
  </div>
</div>
@endsection



