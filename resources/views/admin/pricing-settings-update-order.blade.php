
@extends('admin.layouts.base')

@section('title',__('admin_pricing.update_price_order'))

@section('content')
  <!-- Main Container -->
  <div class="page-body pt-5">
    <div class="container-fluid">
      <div class="page-header">
        <div class="row">
          <div class="col-7">
            <div class="row">
                <div class="col-7">
                    <h3>{{__('admin_pricing.order_prices')}} @if(count($langs) != 1)<span class="title-lang-code">({{ request('lang_code') }})</span>@endif</h3>
                    <ol class="breadcrumb">
                      <li class="breadcrumb-item disappear-500"><a href="{{ route('admin.index') }}"><ins> {{__('admin_pricing.main_page')}}</ins></a></li>
                      <li class="breadcrumb-item disappear-500">{{__('admin_pricing.prices')}}</li>
                      <li class="breadcrumb-item disappear-500">{{__('admin_pricing.order_prices')}}</li>
                    </ol>
                </div>
                <div class="col-5">
                    <!--
                      Refresh the page with selected lang_code.
                    -->
                    @if(count($langs) != 1)
                      @foreach ($langs as $lang)
                        @if (request('lang_code')==$lang->lang_code)
                        <div class="mx-2 d-none">
                          <span class="disabled">({{__('admin_pricing.current_language')}})</span>
                          <img src="{{ asset($lang->icon) }}" alt="">
                        </div>
                        @endif
                      @endforeach
                      <select class="form-select" id="lang-select" name="lang-select" onchange="window.location.href=this.options[this.selectedIndex].value;">
                          @foreach ($langs as $lang)
                          @if ($lang->is_active)
                          <option value="{{ route('admin.pricing-settings.index',['lang_code'=>$lang->lang_code, 'page'=>request('page')]) }}"
                            @if (request('lang_code')==$lang->lang_code)
                                selected
                            @endif
                            >{{ $lang->lang_name }}</option>
                            @endif
                          @endforeach
                      </select>
                    @endif
                </div>
            </div>
          </div>
          <div class="col-5 d-flex justify-content-end">
            <a class="mx-5" href="{{ route('admin.pricing-settings.index',['lang_code' => request('lang_code')]) }}">
              <button type="button" class="btn btn-primary btn-sm m-1">
                <i class="me-2 fa-solid fa-angles-left"></i><span>{{__('admin_pricing.turn_back')}}</span>
              </button>
            </a>
          </div>
        </div>
      </div>
    </div>
    <!-- Sweet Alert -->
    @if(session('success'))
    <script>swal("{{__('admin_pricing.success')}}", "{{__('admin_pricing.success_process')}}", "success");</script>
    @elseif(session('error'))
    <script>swal("{{__('admin_pricing.error')}}", "{{__('admin_pricing.error_process')}}", "error");</script>
    @endif
    <div class="container-fluid">
      <div class="card">
        <div class="card-body">
          <form class="js-validation" action="{{ route('admin.pricing-settings.update',[$prices[0]->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <input type="hidden" id="col-count" value="4">
            <input type="hidden" name="lang_code" value="{{ request('lang_code') }}">
              <div id="container-h-flow" class="container container-h-flow-4-cols">
                @foreach ($prices as $item)
                  @if(!$item->is_active)
                  <div class="item" style="background-image: url({{ asset('admin-asset/gray-scale.png') }}), url({{ asset($item->image_large_screen) }});">
                    <div class="d-flex row m-1">
                      <div class="col-7"></div>
                      <div class="col-2 px-2 ms-3">
                        <a class="btn btn-sm btn-primary px-2" href="{{ route('admin.pricing-settings.edit', [$item->id]) }}">
                          <i class="fa fa-pencil-alt"></i>
                        </a>
                      </div>
                      <div class="col-2" onclick="return confirm('{{__('admin_pricing.delete_question')}}')">
                        <div class="col-2">
                          <a class="btn btn-sm btn-danger px-2" href="{{ route('admin.pricing-settings.destroy', $item->id) }}">
                            <i class="fa fa-trash-alt"></i>
                          </a>
                        </div>
                      </div>
                      <div class="col-1"></div>
                      <p class="item-passive">
                        {{__('admin_pricing.passive')}}
                      </p>
                    </div>
                  @else
                  <div class="item" style="background-image: url({{ asset($item->image_large_screen) }});">
                    <p class="item-title d-none">
                      {{ $item->title }}
                    </p>
                    <div class="d-flex row m-1">
                      <div class="col-7"></div>
                      <div class="col-2 px-2 ms-3">
                        <a class="btn btn-sm btn-primary px-2" href="{{ route('admin.pricing-settings.edit', [$item->id]) }}">
                          <i class="fa fa-pencil-alt"></i>
                        </a>
                      </div>
                      <div class="col-2" onclick="return confirm('{{__('admin_pricing.delete_question')}}')">
                        <div class="col-2">
                          <a class="btn btn-sm btn-danger px-2" href="{{ route('admin.pricing-settings.destroy', $item->id) }}">
                            <i class="fa fa-trash-alt"></i>
                          </a>
                        </div>
                      </div>
                      <div class="col-1"></div>
                  </div>
                  @endif
                  <h3 style="color:rgb(100, 100, 100); font-weight: bold;">{{$item->title}}</h3>
                </div>
                @endforeach
              </div>
            <input type="hidden" name="result_h_flow" id="result-h-flow"></input>
            <div class="mb-4">
              <div class="row">
                <div class="col-md-6 col-lg-8 col-xl-8 col-xxl-9"></div>
                <div class="col-md-6 col-lg-4 col-xl-4 col-xxl-3">
                  <div class="input-group" >
                    <small class="font-weight-bold ms-2">{{__('admin_pricing.drag_to_order')}}</small>
                    <input style="width:100%;" type="submit" class="btn btn btn-primary my-2 mx-1" id="updatepricingorder" name="updatepricingorder" value="{{__('admin_pricing.save_order')}}">
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



