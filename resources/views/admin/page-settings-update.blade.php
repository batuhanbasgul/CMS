@extends('admin.layouts.base')

@section('title',__('admin_page_settings.update_content'))

@section('content')
<!-- Main Container -->
<div class="page-body pt-5">
  <div class="container-fluid">
    <div class="page-header">
      <div class="row">
        <div class="col-7">
            <h3>{{__('admin_page_settings.update_content')}} @if(count($langs) != 1)<span class="title-lang-code">({{ request('lang_code') }})</span>@endif</h3>
            <ol class="breadcrumb">
              <li class="breadcrumb-item disappear-500"><a href="{{ route('admin.index') }}"><ins> {{__('admin_page_settings.main_page')}}</ins></a></li>
              <li class="breadcrumb-item disappear-500">{{__('admin_page_settings.page')}}</li>
              <li class="breadcrumb-item disappear-500">{{ $page->title }}</li>
            </ol>
        </div>
        <div class="col-5 d-flex justify-content-end">
          <!--
            To back.
          -->
        <a href="{{ route('admin.page-settings.index', ['lang_code' => $page->lang_code]) }}">
          <button type="button" class="btn btn-primary btn-sm m-1">
            <i class="me-2 fa-solid fa-angles-left"></i><span>{{__('admin_page_settings.turn_back')}}</span>
          </button>
        </a>
          <!--
            Show on website.
          -->
          <a href="{{ route('slug',$page->slug) }}" target="_blank">
            <button type="button" class="btn btn-primary btn-sm m-1">
              <i class="fas fa-external-link-alt me-1"></i> <span{{__('admin_page_settings.website')}}</span>
            </button>
          </a>
        </div>
      </div>
    </div>
  </div>
  <!-- Sweet Alert -->
  @if(session('success'))
  <script>swal("{{__('admin_page_settings.success')}}", "{{__('admin_page_settings.update_success_context')}}", "success");</script>
  @elseif(session('error'))
  <script>swal("{{__('admin_page_settings.error')}}", "{{__('admin_page_settings.update_error_context')}}", "error");</script>
  @elseif(session('file_extension_error'))
  <script>swal("{{__('admin_page_settings.error')}}", "{{__('admin_page_settings.update_error_file_mime')}}", "error");</script>
  @elseif(session('file_size_error'))
  <script>swal("{{__('admin_page_settings.error')}}", "{{__('admin_page_settings.update_error_file_size')}}", "error");</script>
  @elseif(session('no_image'))
  <script>swal("{{__('admin_page_settings.error')}}", "{{__('admin_gallery.update_error_no_image')}}", "error");</script>
  @endif
  <!-- Container-fluid starts-->
  <div class="container-fluid">
    <div class="card">
      <div class="card-body">
        <ul class="nav nav-tabs border-tab" id="top-tab" role="tablist">
          <li class="nav-item"><a class="nav-link @if (!session('tab_page') || session()->get('tab_page') == 'context')) active @endif" data-bs-toggle="tab"
              href="#top-content" role="tab" aria-controls="top-content" aria-selected="true">
              <i class="fas fa-book-open"></i>{{__('admin_page_settings.content')}}</a></li>
          <li class="nav-item"><a class="nav-link @if (session()->get('tab_page') == 'image_large') active @endif" data-bs-toggle="tab"
              href="#top-image-large" role="tab" aria-controls="top-image-large" aria-selected="false">
              <i class="fas fa-desktop"></i>{{__('admin_page_settings.big_image')}}</a></li>
          <li class="nav-item"><a class="nav-link @if (session()->get('tab_page') == 'image_small') active @endif" data-bs-toggle="tab"
              href="#top-image-small" role="tab" aria-controls="top-image-small" aria-selected="false">
              <i class="fas fa-mobile-alt"></i>{{__('admin_page_settings.small_image')}}</a></li>
          <li class="nav-item"><a class="nav-link @if (session()->get('tab_page') == 'image_banner') active @endif" data-bs-toggle="tab"
              href="#top-image-banner" role="tab" aria-controls="top-image-banner" aria-selected="false">
              <i class="fa-solid fa-panorama"></i>{{__('admin_page_settings.banner_image')}}</a></li>
          <li class="nav-item"><a class="nav-link @if (session()->get('tab_page') == 'image_multi') active @endif" data-bs-toggle="tab"
              href="#top-image-multi" role="tab" aria-controls="top-image-small" aria-selected="false">
              <i class="fas fa-images"></i>{{__('admin_page_images.page_images')}}</a></li>
        </ul>
        <div class="tab-content" id="top-tabContent">
          <div class="tab-pane fade @if (!session()->get('tab_page') || session()->get('tab_page') == 'context')) show active @endif" id="top-content" role="tabpanel" aria-labelledby="top-content-tab">
            <form class="theme-form needs-validation" novalidate="" action="{{ route('admin.page-settings.update',[$page->id]) }}" method="POST">
              @csrf
              @method('PUT')
              <input type="hidden" name="page" value="{{ request('page') }}">
              <h4 class="border-bottom pb-2">{{__('admin_page_settings.page_content')}}</h4>
              <div class="mb-3">
                <label class="col-form-label p-0" for="title">{{__('admin_page_settings.title')}}</label><span class="text-danger">*</span>
                <input class="form-control" id="title" name="title" type="text" placeholder="{{__('admin_page_settings.title_placeholder')}}" value="{{ $page->title }}" required="">
                <div class="valid-feedback">{{__('admin_page_settings.looks_good')}}</div>
              </div>
              <div class="mb-3">
                <label class="col-form-label p-0" for="subtitle">{{__('admin_page_settings.subtitle')}}</label>
                <input class="form-control" id="subtitle" name="subtitle" type="text" placeholder="{{__('admin_page_settings.subtitle_placeholder')}}" value="{{ $page->subtitle }}">
              </div>
              <div class="mb-3">
                <label class="col-form-label p-0" for="author">{{__('admin_page_settings.author_name')}}</label>
                <input class="form-control" id="author" name="author" type="text" placeholder="{{__('admin_page_settings.author_name_placeholder')}}" value="{{ $page->author }}">
              </div>
              <div class="mb-3">
                <label class="col-form-label p-0" for="menu_name">{{__('admin_page_settings.menu_name')}}</label><span class="text-danger">*</span>
                <input class="form-control" id="menu_name" name="menu_name" type="text" placeholder="{{__('admin_page_settings.menu_name_placeholder')}}" value="{{ $page->menu_name }}" required="">
                <div class="valid-feedback">{{__('admin_page_settings.looks_good')}}</div>
              </div>
              <div class="mb-3">
                <label class="col-form-label text-end p-0" for="page_date">{{__('admin_page_settings.date')}}</label>
                <input class="datepicker-here form-control digits" type="text" data-language="tr" id="page_date" name="page_date"
                placeholder="{{__('admin_page_settings.date_placeholder')}}" data-alt-input="true" data-date-format="dd MM yyyy" data-alt-format="j F Y" value="{{ $page->page_date }}">
              </div>
              <div class="mb-3">
                <label class="col-form-label p-0" for="video_embed_code">{{__('admin_page_settings.youtube_code')}}</label>
                <input class="form-control" id="video_embed_code" name="video_embed_code" type="text" placeholder="{{__('admin_page_settings.youtube_code_placeholder')}}" value="{{ $page->video_embed_code }}">
              </div>
              <div class="mb-4">
                <label class="form-label p-0" for="keywords">{{__('admin_page_settings.keywords')}}</label>
                <textarea class="form-control" id="keywords" name="keywords" rows="4" placeholder="{{__('admin_page_settings.keywords_placeholder')}}">{{ $page->keywords }}</textarea>
              </div>
              <div class="mb-4">
                <label class="form-label p-0" for="description">{{__('admin_page_settings.description')}}</label>
                <textarea class="form-control" id="description" name="description" rows="8" placeholder="{{__('admin_page_settings.description_placeholder')}}">{{ $page->description }}</textarea>
              </div>
              <div class="mb-4">
                <label class="form-label p-0" for="short_description">{{__('admin_page_settings.short_description')}}</label>
                <textarea class="form-control" id="short_description" name="short_description" rows="6" placeholder="{{__('admin_page_settings.short_description_placeholder')}}">{{ $page->short_description }}</textarea>
              </div>
              <div>
                <div class="form-check form-switch my-0">
                  <input class="form-check-input custom-switch" type="checkbox" name="is_desktop_active" id="is_desktop_active" @if ($page->is_desktop_active) checked @endif>
                  <label class="form-check-label custom-switch-label" for="is_desktop_active">{{__('admin_page_settings.large_screen_active')}}</label>
                </div>
              </div>
              <div>
                <div class="form-check form-switch my-0">
                  <input class="form-check-input custom-switch" type="checkbox" name="is_mobile_active" id="is_mobile_active" @if ($page->is_mobile_active) checked @endif>
                  <label class="form-check-label custom-switch-label" for="is_mobile_active">{{__('admin_page_settings.small_screen_active')}}</label>
                </div>
              </div>
              <div class="mb-4">
                <div class="row">
                  <div class="col-md-6 col-lg-8 col-xl-8 col-xxl-9"></div>
                  <div class="col-md-6 col-lg-4 col-xl-4 col-xxl-3">
                    <div class="input-group" >
                      <input style="width:100%;" type="submit" class="btn btn btn-primary my-0 mx-1" id="updatepage" name="updatepage" value="{{__('admin_page_settings.update')}}">
                    </div>
                  </div>
                </div>
              </div>
            </form>
          </div>
          <div class="tab-pane fade @if (session()->get('tab_page') == 'image_large') show active @endif" id="top-image-large" role="tabpanel" aria-labelledby="profile-top-tab">
            <form action="{{ route('admin.page-settings.update',[$page->id]) }}" method="POST" enctype="multipart/form-data">
              @csrf
              @method('PUT')
              <input type="hidden" name="page" value="{{ request('page') }}">
              <!-- Image Cropper -->
              <div class="row items-push d-flex align-items-stretch">
                <div class="col-xxl-6">
                  <h4 class="border-bottom pb-2">{{__('admin_page_settings.large_screen_image')}}</h4>
                  <!--
                    RATIO & WIDTH Settings.
                  -->
                  <input type="hidden" class="img-w" value="{{ $large_size->width }}">
                  <input type="hidden" class="img-h" value="{{ $large_size->height }}">
                  <input type="hidden" class="ratio" value="{{ $large_size->ratio }}">
                  <div class="original text-center">
                    @if ($page->image_large_screen)
                    <img id="js-img-cropper" class="img-fluid" src="{{ asset($page->image_large_screen) }}" alt="photo">
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
                      data-method="crop" title="{{__('admin_app_settings.crop_popup')}}">
                      {{__('admin_page_settings.crop')}}
                      </button>
                    </div>
                  </div>
                  <div class="my-4">
                    <input type="file" class="form-control" id="image" name="image_large_screen" accept="image/*">
                    <small class="fw-light text-gray-dark">{{__('admin_page_settings.file_mimes')}}</small>
                    <input type="hidden" name="cropped_data" id="cropped_data"> <!--base64 data transferi-->
                  </div>
                </div>

                <div id="box-2" class="col-xxl-6 d-none">
                  <h4 class="border-bottom pb-2">{{__('admin_page_settings.preview')}}</h4>
                  <!--rightbox-->
                  <div class="img-result text-center">
                    <!-- Result of crop -->
                    <img class="cropped img-fluid cropped-preview" src="" alt="">
                  </div>
                  <div class="row">
                    <input type="submit" class="btn btn-lg btn-primary updateimagebutton" id="updateimagelarge" name="updateimagelarge" value="{{__('admin_page_settings.upload_image')}}">
                  </div>
                </div>
              </div>
              <!-- END Image Cropper -->
            </form>
          </div>
          <div class="tab-pane fade @if (session()->get('tab_page') == 'image_small') show active @endif" id="top-image-small" role="tabpanel" aria-labelledby="contact-top-tab">
            <form action="{{ route('admin.page-settings.update',[$page->id]) }}" method="POST" enctype="multipart/form-data">
              @csrf
              @method('PUT')
              <input type="hidden" name="page" value="{{ request('page') }}">
              <!-- Image Cropper -->
              <div class="row items-push d-flex align-items-stretch">
                <div class="col-xxl-6">
                  <h4 class="border-bottom pb-2">{{__('admin_page_settings.small_screen_image')}}</h4>
                  <!--
                    RATIO & WIDTH Settings.
                  -->
                    <input type="hidden" class="img-w-mobile" value="{{ $mobile_size->width }}">
                    <input type="hidden" class="img-h-mobile" value="{{ $mobile_size->height }}">
                    <input type="hidden" class="ratio-mobile" value="{{ $mobile_size->ratio }}">
                  <div class="original-mobile text-center">
                    @if ($page->image_small_screen)
                    <img id="js-img-cropper" class="img-fluid" src="{{ asset($page->image_small_screen) }}" alt="photo">
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
                      data-method="crop" title="{{__('admin_page_settings.crop_popup')}}">
                      {{__('admin_page_settings.crop')}}
                      </button>
                    </div>
                  </div>
                  <div class="my-4">
                    <input type="file" class="form-control" id="image-mobile" name="image_small_screen" accept="image/*">
                    <small class="fw-light text-gray-dark">{{__('admin_page_settings.file_mimes')}}</small>
                    <input type="hidden" name="cropped_data_mobile" id="cropped_data_mobile"> <!--base64 data transferi-->
                  </div>
                </div>

                <div id="box-2-mobile" class="col-xxl-6 d-none">
                  <h4 class="border-bottom pb-2">{{__('admin_page_settings.preview')}}</h4>
                  <!--rightbox-->
                  <div class="img-result-mobile text-center">
                    <!-- Result of crop -->
                    <img class="cropped-mobile img-fluid cropped-preview" src="" alt="">
                  </div>
                  <div class="row">
                    <input type="submit" class="btn btn-lg btn-primary updateimagebutton" id="updateimagesmall" name="updateimagesmall" value="{{__('admin_page_settings.upload_image')}}">
                  </div>
                </div>
              </div>
              <!-- END Image Cropper -->
            </form>
          </div>
          <div class="tab-pane fade @if (session()->get('tab_page') == 'image_banner') show active @endif" id="top-image-banner" role="tabpanel" aria-labelledby="top-image-banner-tab">
            <form action="{{ route('admin.page-settings.update',[$page->id]) }}" method="POST" enctype="multipart/form-data">
              @csrf
              @method('PUT')
              <input type="hidden" name="page" value="{{ request('page') }}">
              <!-- Image Cropper -->
              <div class="row items-push d-flex align-items-stretch">
                <div class="col-xxl-6">
                  <h4 class="border-bottom pb-2">{{__('admin_page_settings.banner_image')}}</h4>
                  <!--
                    RATIO & WIDTH Settings.
                  -->
                    <input type="hidden" class="img-w-banner" value="{{ $banner_size->width }}">
                    <input type="hidden" class="img-h-banner" value="{{ $banner_size->height }}">
                    <input type="hidden" class="ratio-banner" value="{{ $banner_size->ratio }}">
                  <div class="original-banner text-center">
                    @if ($page->hero)
                    <img id="js-img-cropper" class="img-fluid" src="{{ asset($page->hero) }}" alt="photo">
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
                      data-method="crop" title="{{__('admin_page_settings.crop_popup')}}">
                      {{__('admin_page_settings.crop')}}
                      </button>
                    </div>
                  </div>
                  <div class="my-4">
                    <input type="file" class="form-control" id="image-banner" name="hero" accept="image/*">
                    <small class="fw-light text-gray-dark">{{__('admin_page_settings.file_mimes')}}</small>
                    <input type="hidden" name="cropped_data_banner" id="cropped_data_banner"> <!--base64 data transferi-->
                  </div>
                </div>

                <div id="box-2-banner" class="col-xxl-6 d-none">
                  <h4 class="border-bottom pb-2">{{__('admin_page_settings.preview')}}</h4>
                  <!--rightbox-->
                  <div class="img-result-banner text-center">
                    <!-- Result of crop -->
                    <img class="cropped-banner img-fluid cropped-preview" src="" alt="">
                  </div>
                  <div class="row">
                    <input type="submit" class="btn btn-lg btn-primary updateimagebutton" id="updateimagebanner" name="updateimagebanner" value="{{__('admin_page_settings.upload_image')}}">
                  </div>
                </div>
              </div>
              <!-- END Image Cropper -->
            </form>
          </div>
          <div class="tab-pane fade @if (session()->get('tab_page') == 'image_multi') show active @endif" id="top-image-multi" role="tabpanel" aria-labelledby="top-image-banner-tab">
            <form action="{{ route('admin.page-settings.update',[$page->id]) }}" method="POST" enctype="multipart/form-data">
              @csrf
              @method('PUT')
              <input type="hidden" name="page" value="{{ request('page') }}">
              <!-- Image Cropper -->
              <div class="row items-push d-flex align-items-stretch">
                <div class="col-xxl-6">
                  <h4 class="border-bottom pb-2">{{__('admin_page_settings.add_image')}}</h4>
                  <!--
                    RATIO & WIDTH Settings.
                  -->
                    <input type="hidden" class="img-w-multi" value="{{ $image_size->width }}">
                    <input type="hidden" class="img-h-multi" value="{{ $image_size->height }}">
                    <input type="hidden" class="ratio-multi" value="{{ $image_size->ratio }}">

                  <div class="original-multi text-center">
                    <img id="js-img-cropper" class="img-fluid" src="{{ asset('uploads/media/constant/placeholder.png') }}" alt="photo">
                  </div>
                  <div class="text-center">
                    <p class="my-2" class="fw-ligth">{{ $image_size->width }}x{{ $image_size->height }} px</p>
                  </div>
                  <div id="box-multi" class="block-content text-center d-none cropper-buttons">
                    <div class="row">
                      <button type="button" class="btn btn-primary crop-multi" data-toggle="cropper-multi"
                      data-method="crop" title="{{__('admin_page_settings.crop_popup')}}">
                      {{__('admin_page_settings.crop')}}
                      </button>
                    </div>
                  </div>
                  <div class="my-4">
                    <input type="file" class="form-control" id="image-multi" name="image" accept="image/*">
                    <small class="fw-light text-gray-dark">{{__('admin_page_settings.file_mimes')}}</small>
                    <input type="hidden" name="cropped_data_multi" id="cropped_data_multi"> <!--base64 data transferi-->
                  </div>
                </div>

                <div id="box-2-multi" class="col-xxl-6 d-none">
                  <h4 class="border-bottom pb-2">{{__('admin_page_settings.preview')}}</h4>
                  <!--rightbox-->
                  <div class="img-result-multi text-center">
                    <!-- Result of crop -->
                    <img class="cropped-multi img-fluid cropped-preview" src="" alt="">
                  </div>
                  <div class="row">
                    <input type="submit" class="btn btn-lg btn-primary updateimagebutton" id="updateimagemulti" name="updateimagemulti" value="{{__('admin_page_settings.upload_image')}}">
                  </div>
                </div>
              </div>
              <!-- END Image Cropper -->
            </form>
            @if (0 < $images->count())
            <div class="row">
              <div class="col-12">
                <h4 class="border-bottom border-top pb-2 pt-3">{{__('admin_page_settings.page_images')}}
                  <a href="{{ route('admin.page-images-settings.edit-order', ['page_id' => $page->id, 'lang_code' => $page->lang_code]) }}">
                    <button type="button" class="btn btn-light">
                      <i class="fas fa-stream me-2"></i><span>{{__('admin_page_settings.order_images')}}</span>
                    </button>
                  </a>
                </h4>
              </div>
            </div>

            <div class="card">
              <div class="card-body">
                <div class="row gallery my-gallery" id="aniimated-thumbnials10" itemscope="">
                  @foreach ($images as $image)
                  <figure class="col-md-3 img-hover hover-11" itemprop="associatedMedia" itemscope="">
                    <a href="{{ asset($image->image) }}" itemprop="contentUrl" data-size="1000x1000">
                      <div>
                        <img src="{{ asset($image->image) }}" itemprop="thumbnail" alt="Image description" @if (!$image->is_active) style="filter:grayscale(100%)" @endif>
                      </div>
                    </a>
                    <figcaption itemprop="caption description">{{ $page->title }}</figcaption>
                  </figure>
                  @endforeach
                </div>
              </div>
            </div>
            <!-- Root element of PhotoSwipe. Must have class pswp.-->
            <div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">
              <!--
                Background of PhotoSwipe.
                It's a separate element, as animating opacity is faster than rgba().
                -->
              <div class="pswp__bg"></div>
              <!-- Slides wrapper with overflow:hidden.-->
              <div class="pswp__scroll-wrap">
                <!-- Container that holds slides. PhotoSwipe keeps only 3 slides in DOM to save memory.-->
                <!-- don't modify these 3 pswp__item elements, data is added later on.-->
                <div class="pswp__container">
                  <div class="pswp__item"></div>
                  <div class="pswp__item"></div>
                  <div class="pswp__item"></div>
                </div>
                <!-- Default (PhotoSwipeUI_Default) interface on top of sliding area. Can be changed.-->
                <div class="pswp__ui pswp__ui--hidden">
                  <div class="pswp__top-bar">
                    <!-- Controls are self-explanatory. Order can be changed.-->
                    <div class="pswp__counter"></div>
                    <button class="pswp__button pswp__button--close" title="Close (Esc)"></button>
                    <button class="pswp__button pswp__button--fs" title="Toggle fullscreen"></button>
                    <button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button>
                    <!-- Preloader demo https://codepen.io/dimsemenov/pen/yyBWoR-->
                    <!-- element will get class pswp__preloader--active when preloader is running-->
                    <div class="pswp__preloader">
                      <div class="pswp__preloader__icn">
                        <div class="pswp__preloader__cut">
                          <div class="pswp__preloader__donut"></div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
                    <div class="pswp__share-tooltip"></div>
                  </div>
                  <button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)"></button>
                  <button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)"></button>
                  <div class="pswp__caption">
                    <div class="pswp__caption__center"></div>
                  </div>
                </div>
              </div>
            </div>
            @endif
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
