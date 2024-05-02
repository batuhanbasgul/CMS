@extends('admin.layouts.base')

@section('title',__('admin_user.update_content'))

@section('content')
<!-- Main Container -->
<div class="page-body pt-5">
  <div class="container-fluid">
    <div class="page-header">
      <div class="row">
        <div class="col-7">
            <h3>{{__('admin_user.update_content')}}</h3>
            <ol class="breadcrumb">
              <li class="breadcrumb-item disappear-500"><a href="{{ route('admin.index') }}"><ins>{{__('admin_user.main_page')}}</ins></a></li>
              <li class="breadcrumb-item disappear-500">{{__('admin_user.users')}}</li>
              <li class="breadcrumb-item disappear-500">{{ $user->name }}</li>
            </ol>
        </div>
        <div class="col-5 d-flex justify-content-end">
          <a class="mx-5" href="{{ route('admin.user-settings.index',['lang_code' => request('lang_code')]) }}">
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
  <script>swal("{{__('admin_user.success')}}", "{{__('admin_user.update_success_context')}}", "success");</script>
  @elseif(session('error'))
  <script>swal("{{__('admin_user.error')}}", "{{__('admin_user.update_error_context')}}", "error");</script>
  @elseif(session('file_extension_error'))
  <script>swal("{{__('admin_user.error')}}", "{{__('admin_user.update_error_file_mime')}}", "error");</script>
  @elseif(session('file_size_error'))
  <script>swal("{{__('admin_user.error')}}", "{{__('admin_user.update_error_file_size')}}", "error");</script>
  @elseif(session('error_pwd'))
  <script>swal("{{__('admin_user.error')}}", "{{__('admin_user.passwords_did_not_match')}}", "error");</script>
  @elseif(session('old_pwd'))
  <script>swal("{{__('admin_user.error')}}", "{{__('admin_user.wrong_old_password')}}", "error");</script>
  @endif
  <!-- Container-fluid starts-->
  <div class="container-fluid">
    <div class="card">
      <div class="card-body">
        <ul class="nav nav-tabs border-tab" id="top-tab" role="tablist">
          <li class="nav-item"><a class="nav-link @if (!session('tab_page') || session()->get('tab_page') == 'user_info')) active @endif" data-bs-toggle="tab"
              href="#top-user-info" role="tab" aria-controls="top-user-info" aria-selected="true">
              <i class="fas fa-book-open"></i>{{__('admin_user.user_info')}}</a></li>
          <li class="nav-item"><a class="nav-link @if (session()->get('tab_page') == 'profile_image') active @endif" data-bs-toggle="tab"
              href="#top-profile-image" role="tab" aria-controls="top-profile-image" aria-selected="false">
              <i class="fa-solid fa-image-portrait"></i>{{__('admin_user.profile_image')}}</a></li>
          <li class="nav-item"><a class="nav-link @if (session()->get('tab_page') == 'password') active @endif" data-bs-toggle="tab"
              href="#top-password" role="tab" aria-controls="top-password" aria-selected="false">
              <i class="fa-solid fa-key"></i>{{__('admin_user.change_password')}}</a></li>
        </ul>
        <div class="tab-content" id="top-tabContent">
          <div class="tab-pane fade @if (!session()->get('tab_page') || session()->get('tab_page') == 'user_info')) show active @endif" id="top-user-info" role="tabpanel" aria-labelledby="top-user-info">
            <form class="theme-form needs-validation" novalidate="" action="{{ route('admin.user-settings.update',[$user->id]) }}" method="POST">
              @csrf
              @method('PUT')
              <h4 class="border-bottom pb-2">{{__('admin_user.user_info')}}</h4>
              <div class="mb-3">
                <label class="col-form-label p-0" for="name">{{__('admin_user.user_name')}}</label><span class="text-danger">*</span>
                <input class="form-control" id="name" name="name" type="text" placeholder="{{__('admin_user.user_name_placeholder')}}" value="{{ $user->name }}" required="">
                <div class="valid-feedback">{{__('admin_user.looks_good')}}</div>
              </div>
              <div class="mb-3">
                <label class="col-form-label p-0" for="user_title">{{__('admin_user.user_title')}}</label>
                <input class="form-control" id="user_title" name="user_title" type="text" placeholder="{{__('admin_user.user_title_placeholder')}}" value="{{ $user->title }}">
              </div>
              <div class="mb-3">
                <label class="col-form-label p-0" for="email">{{__('admin_user.email')}}</label>
                <input class="form-control" id="email" name="email" type="email" placeholder="{{__('admin_user.email_placeholder')}}" value="{{ $user->email }}" disabled>
              </div>
              <div class="mb-3">
                <label class="col-form-label p-0" for="user_role">{{__('admin_user.authority')}}</label>
                <input class="form-control" id="user_role" name="user_role" type="text" placeholder="{{__('admin_user.authority_placeholder')}}" value="{{ $user->user_role }}" disabled>
              </div>
              @if ($user->id != Auth::id())
              <div>
                <div class="form-check form-switch my-0">
                  <input class="form-check-input custom-switch" type="checkbox" name="is_active" id="is_active" @if ($user->is_active) checked @endif>
                  <label class="form-check-label custom-switch-label" for="is_active">{{__('admin_user.is_active')}}</label>
                </div>
              </div>
              @endif
              <div class="mb-4">
                <div class="row">
                  <div class="col-md-6 col-lg-8 col-xl-8 col-xxl-9"></div>
                  <div class="col-md-6 col-lg-4 col-xl-4 col-xxl-3">
                    <div class="input-group" >
                      <input style="width:100%;" type="submit" class="btn btn btn-primary my-0 mx-1" id="updateuserinfo" name="updateuserinfo" value="{{__('admin_user.update')}}">
                    </div>
                  </div>
                </div>
              </div>
            </form>
          </div>
          <div class="tab-pane fade @if (session()->get('tab_page') == 'profile_image') show active @endif" id="top-profile-image" role="tabpanel" aria-labelledby="top-profile-image">
            <form action="{{ route('admin.user-settings.update',[$user->id]) }}" method="POST" enctype="multipart/form-data">
              @csrf
              @method('PUT')
              <input type="hidden" name="page" value="{{ request('page') }}">
              <!-- Image Cropper -->
              <div class="row items-push d-flex align-items-stretch">
                <div class="col-xxl-6">
                  <h4 class="border-bottom pb-2">{{__('admin_user.profile_image')}}</h4>
                  <!--
                    RATIO & WIDTH Settings.
                  -->
                  <input type="hidden" class="img-w" value="{{ $image_size->width }}">
                  <input type="hidden" class="img-h" value="{{ $image_size->height }}">
                  <input type="hidden" class="ratio" value="{{ $image_size->ratio }}">
                  <div class="original text-center">
                    @if ($user->profile_image)
                    <img id="js-img-cropper" class="img-fluid" src="{{ asset($user->profile_image) }}" alt="photo">
                    @else
                    <img id="js-img-cropper" class="img-fluid" src="{{ asset('uploads/media/constant/placeholder.png') }}" alt="photo">
                    @endif
                  </div>
                  <div class="text-center">
                    <p class="my-2" class="fw-ligth">{{ $image_size->width }}x{{ $image_size->height }} px</p>
                  </div>
                  <div id="box" class="block-content text-center d-none cropper-buttons">
                    <div class="row">
                      <button type="button" class="btn btn-primary crop" data-toggle="cropper"
                      data-method="crop" title="{{__('admin_user.crop_popup')}}">
                      {{__('admin_user.crop')}}
                      </button>
                    </div>
                  </div>
                  <div class="my-4">
                    <input type="file" class="form-control" id="image" name="image_large_screen" accept="image/*">
                    <small class="fw-light text-gray-dark">{{__('admin_user.file_mimes')}}</small>
                    <input type="hidden" name="cropped_data" id="cropped_data"> <!--base64 data transferi-->
                  </div>
                </div>

                <div id="box-2" class="col-xxl-6 d-none">
                  <h4 class="border-bottom pb-2">{{__('admin_user.preview')}}</h4>
                  <!--rightbox-->
                  <div class="img-result text-center">
                    <!-- Result of crop -->
                    <img class="cropped img-fluid cropped-preview" src="" alt="">
                  </div>
                  <div class="row">
                    <input type="submit" class="btn btn-lg btn-primary updateimagebutton" id="updateimagelarge" name="updateimagelarge" value="{{__('admin_user.upload_image')}}">
                  </div>
                </div>
              </div>
              <!-- END Image Cropper -->
            </form>
          </div>
          <div class="tab-pane fade @if (session()->get('tab_page') == 'password') show active @endif" id="top-password" role="tabpanel" aria-labelledby="top-password">
            <form class="theme-form needs-validation" novalidate="" action="{{ route('admin.user-settings.update',[$user->id]) }}" method="POST">
              @csrf
              @method('PUT')
              <h4 class="border-bottom pb-2">{{__('admin_user.change_password')}}</h4>
              <div class="mb-3">
                <label class="col-form-label p-0" for="password_old">{{__('admin_user.old_password')}}</label><span class="text-danger">*</span>
                <input class="form-control" id="password_old" name="password_old" type="password" placeholder="{{__('admin_user.old_password_placeholder')}}" value="{{ old('password_old') }}" required="">
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
              <div class="mb-4">
                <div class="row">
                  <div class="col-md-6 col-lg-8 col-xl-8 col-xxl-9"></div>
                  <div class="col-md-6 col-lg-4 col-xl-4 col-xxl-3">
                    <div class="input-group" >
                      <input style="width:100%;" type="submit" class="btn btn btn-primary my-0 mx-1" id="updateuserpwd" name="updateuserpwd" value="{{__('admin_user.update')}}">
                    </div>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Container-fluid Ends-->
</div>
<!-- END Main Container -->

@endsection
