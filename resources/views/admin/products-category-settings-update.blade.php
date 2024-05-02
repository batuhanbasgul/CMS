@extends('admin.layouts.base')

@section('title',__('admin_products_category.update_content'))

@section('content')
<!-- Main Container -->
<div class="page-body pt-5">
  <div class="container-fluid">
    <div class="page-header">
      <div class="row">
        <div class="col-7">
            <h3>{{__('admin_products_category.update_content')}}</h3>
            <ol class="breadcrumb">
              <li class="breadcrumb-item disappear-500"><a href="{{ route('admin.index') }}"><ins> {{__('admin_products_category.main_page')}}</ins></a></li>
              <li class="breadcrumb-item disappear-500">{{__('admin_products_category.category')}}</li>
              <li class="breadcrumb-item disappear-500">{{ $category->name }}</li>
            </ol>
        </div>
        <div class="col-5 d-flex justify-content-end">
          <a class="mx-5" href="{{ route('admin.products-category-settings.index', ['lang_code' => $category->lang_code]) }}">
            <button type="button" class="btn btn-primary btn-sm m-1">
                <i class="me-2 fa-solid fa-angles-left"></i><span>{{__('admin_products_category.turn_back')}}</span>
            </button>
          </a>
        </div>
      </div>
    </div>
  </div>
  <!-- Sweet Alert -->
  @if(session('success'))
  <script>swal("{{__('admin_products_category.success')}}", "{{__('admin_products_category.update_success_context')}}", "success");</script>
  @elseif(session('error'))
  <script>swal("{{__('admin_products_category.error')}}", "{{__('admin_products_category.update_error_context')}}", "error");</script>
  @elseif(session('file_extension_error'))
  <script>swal("{{__('admin_products_category.error')}}", "{{__('admin_products_category.update_error_file_mime')}}", "error");</script>
  @elseif(session('file_size_error'))
  <script>swal("{{__('admin_products_category.error')}}", "{{__('admin_products_category.update_error_file_size')}}", "error");</script>
  @endif
  <!-- Container-fluid starts-->
  <div class="container-fluid">
    <div class="card">
      <div class="card-body">
        <ul class="nav nav-tabs border-tab" id="top-tab" role="tablist">
          <li class="nav-item"><a class="nav-link @if (!session('tab_page') || session()->get('tab_page') == 'order')) active @endif" data-bs-toggle="tab"
              href="#top-order" role="tab" aria-controls="top-order" aria-selected="true">
              <i class="fas fa-stream me-2"></i>{{__('admin_products_category.category_products_order')}}</a></li>
          <li class="nav-item"><a class="nav-link @if (session()->get('tab_page') == 'context') active @endif" data-bs-toggle="tab"
              href="#top-content" role="tab" aria-controls="top-content" aria-selected="false">
              <i class="fas fa-book-open"></i>{{__('admin_products_category.category_content')}}</a></li>
          <li class="nav-item"><a class="nav-link @if (session()->get('tab_page') == 'image_large') active @endif" data-bs-toggle="tab"
              href="#top-image-large" role="tab" aria-controls="top-image-large" aria-selected="false">
              <i class="fas fa-desktop"></i>{{__('admin_products_category.big_image')}}</a></li>
          <li class="nav-item"><a class="nav-link @if (session()->get('tab_page') == 'image_small') active @endif" data-bs-toggle="tab"
              href="#top-image-small" role="tab" aria-controls="top-image-small" aria-selected="false">
              <i class="fas fa-mobile-alt"></i>{{__('admin_products_category.small_image')}}</a></li>
          <li class="nav-item"><a class="nav-link @if (session()->get('tab_page') == 'image_banner') active @endif" data-bs-toggle="tab"
              href="#top-image-banner" role="tab" aria-controls="top-image-banner" aria-selected="false">
              <i class="fa-solid fa-panorama"></i>{{__('admin_products_category.banner_image')}}</a></li>
        </ul>
        <div class="tab-content" id="top-tabContent">
          <div class="tab-pane fade @if (!session()->get('tab_page') || session()->get('tab_page') == 'order')) show active @endif" id="top-order" role="tabpanel" aria-labelledby="top-order-tab">
            <form class="js-validation" action="{{ route('admin.products-category-settings.update',[$category->id]) }}" method="POST" enctype="multipart/form-data">
              @csrf
              @method('PUT')
              <input type="hidden" name="category_id" value="{{ $category->id }}">
              <input type="hidden" id="col-count" value="4">
              <input type="hidden" name="lang_code" value="{{ request('lang_code') }}">
                <div id="container-h-flow" class="container container-h-flow-4-cols">
                  @if (count($category_products)<=0)
                      Kategoriye ait ürün bulunamadı.
                  @endif
                  @foreach ($category_products as $item)
                    @if(!$item->is_active)
                    <div class="item" style="background-image: url({{ asset('admin-asset/gray-scale.png') }}), url({{ asset($item->image_large_screen) }});">
                      <div class="d-flex row m-1">
                        <div class="col-7"></div>
                        <div class="col-2 px-2 ms-3">
                          <a class="btn btn-sm btn-primary px-2" href="{{ route('admin.products-settings.edit', [$item->id]) }}">
                            <i class="fa fa-pencil-alt"></i>
                          </a>
                        </div>
                        <div class="col-2" onclick="return confirm('{{__('admin_products_category.delete_question')}}')">
                          <div class="col-2">
                            <a class="btn btn-sm btn-danger px-2" href="{{ route('admin.products-settings.destroy', $item->id) }}">
                              <i class="fa fa-trash-alt"></i>
                            </a>
                          </div>
                        </div>
                        <div class="col-1"></div>
                        <p class="item-passive">
                            {{__('admin_products_category.passive')}}
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
                          <a class="btn btn-sm btn-primary px-2" href="{{ route('admin.products-settings.edit', [$item->id]) }}">
                            <i class="fa fa-pencil-alt"></i>
                          </a>
                        </div>
                        <div class="col-2" onclick="return confirm('{{__('admin_products_category.delete_question')}}')">
                          <div class="col-2">
                            <a class="btn btn-sm btn-danger px-2" href="{{ route('admin.products-settings.destroy', $item->id) }}">
                              <i class="fa fa-trash-alt"></i>
                            </a>
                          </div>
                        </div>
                        <div class="col-1"></div>
                    </div>
                    @endif
                    <h3 style="color:rgb(100, 100, 100); font-weight: bold;">{{$item->name}}</h3>
                  </div>
                  @endforeach
                </div>
              <input type="hidden" name="result_h_flow" id="result-h-flow"></input>
              <div class="mb-4">
                <div class="row">
                  <div class="col-md-6 col-lg-8 col-xl-8 col-xxl-9"></div>
                  <div class="col-md-6 col-lg-4 col-xl-4 col-xxl-3">
                    <div class="input-group" >
                      <small class="font-weight-bold ms-2">{{__('admin_products_category.drag_to_order')}}</small>
                      <input style="width:100%;" type="submit" class="btn btn btn-primary my-2 mx-1" id="updatecategoryproducts" name="updatecategoryproducts" value="{{__('admin_products_category.save_order')}}">
                    </div>
                  </div>
                </div>
              </div>
            </form>
          </div>
          <div class="tab-pane fade @if (session()->get('tab_page') == 'context') show active @endif" id="top-content" role="tabpanel" aria-labelledby="top-content-tab">
            <form class="theme-form needs-validation" novalidate="" action="{{ route('admin.products-category-settings.update',[$category->id]) }}" method="POST">
              @csrf
              @method('PUT')
              <h4 class="border-bottom pb-2">{{__('admin_products_category.category_content')}}</h4>
              <div class="mb-3">
                <label class="col-form-label p-0" for="name">{{__('admin_products_category.category_name')}}</label><span class="text-danger">*</span>
                <input class="form-control" id="name" name="name" type="text" placeholder="{{__('admin_products_category.category_name_placeholder')}}" value="{{ $category->name }}" required="">
                <div class="valid-feedback">{{__('admin_products_category.looks_good')}}</div>
              </div>
              <div class="mb-4">
                <label class="form-label p-0" for="keywords">{{__('admin_products_category.keywords')}}</label>
                <textarea class="form-control" id="keywords" name="keywords" rows="4" placeholder="{{__('admin_products_category.keywords_placeholder')}}">{{ $category->keywords }}</textarea>
              </div>
              <div class="mb-4">
                <label class="form-label p-0" for="description">{{__('admin_products_category.description')}}</label>
                <textarea class="form-control" id="description" name="description" rows="8" placeholder="{{__('admin_products_category.description_placeholder')}}">{{ $category->description }}</textarea>
              </div>
              <div>
                <div class="form-check form-switch my-0">
                  <input class="form-check-input custom-switch" type="checkbox" name="is_active" id="is_active" @if ($category->is_active) checked @endif>
                  <label class="form-check-label custom-switch-label" for="is_active">{{__('admin_products_category.is_active')}}</label>
                </div>
              </div>
              <div class="mb-4">
                <div class="row">
                  <div class="col-md-6 col-lg-8 col-xl-8 col-xxl-9"></div>
                  <div class="col-md-6 col-lg-4 col-xl-4 col-xxl-3">
                    <div class="input-group" >
                      <input style="width:100%;" type="submit" class="btn btn btn-primary my-0 mx-1" id="updatecategorysettings" name="updatecategorysettings" value="{{__('admin_products_category.update')}}">
                    </div>
                  </div>
                </div>
              </div>
            </form>
          </div>
          <div class="tab-pane fade @if (session()->get('tab_page') == 'image_large') show active @endif" id="top-image-large" role="tabpanel" aria-labelledby="top-image-large-tab">
            <form action="{{ route('admin.products-category-settings.update',[$category->id]) }}" method="POST" enctype="multipart/form-data">
              @csrf
              @method('PUT')
              <input type="hidden" name="page" value="{{ request('page') }}">
              <!-- Image Cropper -->
              <div class="row items-push d-flex align-items-stretch">
                <div class="col-xxl-6">
                  <h4 class="border-bottom pb-2">{{__('admin_products_category.large_screen_image')}}</h4>
                  <!--
                    RATIO & WIDTH Settings.
                  -->
                  <input type="hidden" class="img-w" value="{{ $large_size->width }}">
                  <input type="hidden" class="img-h" value="{{ $large_size->height }}">
                  <input type="hidden" class="ratio" value="{{ $large_size->ratio }}">
                  <div class="original text-center">
                    @if ($category->image_large_screen)
                    <img id="js-img-cropper" class="img-fluid" src="{{ asset($category->image_large_screen) }}" alt="photo">
                    @else
                    <img id="js-img-cropper" class="img-fluid" src="{{ asset('uploads/media/constant/placeholder.png') }}" alt="photo">
                    @endif
                  </div>
                  <div class="text-center">
                    <p class="my-2" class="fw-ligth">{{ $large_size->width }}x{{ $large_size->height }} px</p>
                  </div>
                  <div id="box" class="block-content text-center d-none cropper-buttons">
                    <div class="row">
                      <button type="button" class="btn btn-primary crop" data-toggle="cropper"
                      data-method="crop" title="{{__('admin_products_category.crop_popup')}}">
                      {{__('admin_products_category.crop')}}
                      </button>
                    </div>
                  </div>
                  <div class="my-4">
                    <input type="file" class="form-control" id="image" name="image_large_screen" accept="image/*">
                    <small class="fw-light text-gray-dark">{{__('admin_products_category.file_mimes')}}</small>
                    <input type="hidden" name="cropped_data" id="cropped_data"> <!--base64 data transferi-->
                  </div>
                </div>

                <div id="box-2" class="col-xxl-6 d-none">
                  <h4 class="border-bottom pb-2">{{__('admin_products_category.preview')}}</h4>
                  <!--rightbox-->
                  <div class="img-result text-center">
                    <!-- Result of crop -->
                    <img class="cropped img-fluid cropped-preview" src="" alt="">
                  </div>
                  <div class="row">
                    <input type="submit" class="btn btn-lg btn-primary updateimagebutton" id="updateimagelarge" name="updateimagelarge" value="{{__('admin_products_category.upload_image')}}">
                  </div>
                </div>
              </div>
              <!-- END Image Cropper -->
            </form>
          </div>
          <div class="tab-pane fade @if (session()->get('tab_page') == 'image_small') show active @endif" id="top-image-small" role="tabpanel" aria-labelledby="top-image-small-tab">
            <form action="{{ route('admin.products-category-settings.update',[$category->id]) }}" method="POST" enctype="multipart/form-data">
              @csrf
              @method('PUT')
              <input type="hidden" name="page" value="{{ request('page') }}">
              <!-- Image Cropper -->
              <div class="row items-push d-flex align-items-stretch">
                <div class="col-xxl-6">
                  <h4 class="border-bottom pb-2"></h4>{{__('admin_products_category.small_screen_image')}}
                  <!--
                    RATIO & WIDTH Settings.
                  -->
                    <input type="hidden" class="img-w-mobile" value="{{ $mobile_size->width }}">
                    <input type="hidden" class="img-h-mobile" value="{{ $mobile_size->height }}">
                    <input type="hidden" class="ratio-mobile" value="{{ $mobile_size->ratio }}">
                  <div class="original-mobile text-center">
                    @if ($category->image_small_screen)
                    <img id="js-img-cropper" class="img-fluid" src="{{ asset($category->image_small_screen) }}" alt="photo">
                    @else
                    <img id="js-img-cropper" class="img-fluid" src="{{ asset('uploads/media/constant/placeholder.png') }}" alt="photo">
                    @endif
                  </div>
                  <div class="text-center">
                    <p class="my-2" class="fw-ligth">{{ $mobile_size->width }}x{{ $mobile_size->height }} px</p>
                  </div>
                  <div id="box-mobile" class="block-content text-center d-none cropper-buttons">
                    <div class="row">
                      <button type="button" class="btn btn-primary crop-mobile" data-toggle="cropper-mobile"
                      data-method="crop" title="{{__('admin_products_category.crop_popup')}}">
                      {{__('admin_products_category.crop')}}
                      </button>
                    </div>
                  </div>
                  <div class="my-4">
                    <input type="file" class="form-control" id="image-mobile" name="image_small_screen" accept="image/*">
                    <small class="fw-light text-gray-dark">{{__('admin_products_category.file_mimes')}}</small>
                    <input type="hidden" name="cropped_data_mobile" id="cropped_data_mobile"> <!--base64 data transferi-->
                  </div>
                </div>

                <div id="box-2-mobile" class="col-xxl-6 d-none">
                  <h4 class="border-bottom pb-2">{{__('admin_products_category.preview')}}</h4>
                  <!--rightbox-->
                  <div class="img-result-mobile text-center">
                    <!-- Result of crop -->
                    <img class="cropped-mobile img-fluid cropped-preview" src="" alt="">
                  </div>
                  <div class="row">
                    <input type="submit" class="btn btn-lg btn-primary updateimagebutton" id="updateimagesmall" name="updateimagesmall" value="{{__('admin_products_category.upload_image')}}">
                  </div>
                </div>
              </div>
              <!-- END Image Cropper -->
            </form>
          </div>
          <div class="tab-pane fade @if (session()->get('tab_page') == 'image_banner') show active @endif" id="top-image-banner" role="tabpanel" aria-labelledby="top-image-banner-tab">
            <form action="{{ route('admin.products-category-settings.update',[$category->id]) }}" method="POST" enctype="multipart/form-data">
              @csrf
              @method('PUT')
              <input type="hidden" name="page" value="{{ request('page') }}">
              <!-- Image Cropper -->
              <div class="row items-push d-flex align-items-stretch">
                <div class="col-xxl-6">
                  <h4 class="border-bottom pb-2">{{__('admin_products_category.banner_image')}}</h4>
                  <!--
                    RATIO & WIDTH Settings.
                  -->
                    <input type="hidden" class="img-w-banner" value="{{ $banner_size->width }}">
                    <input type="hidden" class="img-h-banner" value="{{ $banner_size->height }}">
                    <input type="hidden" class="ratio-banner" value="{{ $banner_size->ratio }}">
                  <div class="original-banner text-center">
                    @if ($category->hero)
                    <img id="js-img-cropper" class="img-fluid" src="{{ asset($category->hero) }}" alt="photo">
                    @else
                    <img id="js-img-cropper" class="img-fluid" src="{{ asset('uploads/media/constant/placeholder.png') }}" alt="photo">
                    @endif
                  </div>
                  <div class="text-center">
                    <p class="my-2" class="fw-ligth">{{ $banner_size->width }}x{{ $banner_size->height }} px</p>
                  </div>
                  <div id="box-banner" class="block-content text-center d-none cropper-buttons">
                    <div class="row">
                      <button type="button" class="btn btn-primary crop-banner" data-toggle="cropper-banner"
                      data-method="crop" title="{{__('admin_products_category.crop_popup')}}">
                      {{__('admin_products_category.crop')}}
                      </button>
                    </div>
                  </div>
                  <div class="my-4">
                    <input type="file" class="form-control" id="image-banner" name="hero" accept="image/*">
                    <small class="fw-light text-gray-dark">{{__('admin_products_category.file_mimes')}}</small>
                    <input type="hidden" name="cropped_data_banner" id="cropped_data_banner"> <!--base64 data transferi-->
                  </div>
                </div>

                <div id="box-2-banner" class="col-xxl-6 d-none">
                  <h4 class="border-bottom pb-2">{{__('admin_products_category.preview')}}</h4>
                  <!--rightbox-->
                  <div class="img-result-banner text-center">
                    <!-- Result of crop -->
                    <img class="cropped-banner img-fluid cropped-preview" src="" alt="">
                  </div>
                  <div class="row">
                    <input type="submit" class="btn btn-lg btn-primary updateimagebutton" id="updateimagebanner" name="updateimagebanner" value="{{__('admin_products_category.upload_image')}}">
                  </div>
                </div>
              </div>
              <!-- END Image Cropper -->
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Container-fluid Ends-->
</div>
<!-- END Main Container -->
<script>
  ClassicEditor
      .create( document.querySelector( '#description' ))
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
