<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
      content="">
    <meta name="keywords"
      content="">
    <meta name="author" content="Fenrir Software">
    <link rel="icon" href="{{ asset('uploads/media/constant/icon.png') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('uploads/media/constant/icon.png') }}" type="image/x-icon">
    <title>{{__('auth_pages.reset_password')}}</title>
    <!-- Google font-->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&amp;display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&amp;display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,400;0,500;0,600;0,700;0,800;0,900;1,300;1,400;1,500;1,600;1,700;1,800;1,900&amp;display=swap" rel="stylesheet">
    <!-- Font Awesome-->
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-asset/css/fontawesome.css') }}">
    <!-- ico-font-->
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-asset/css/icofont.css') }}">
    <!-- Themify icon-->
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-asset/css/themify.css') }}">
    <!-- Flag icon-->
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-asset/css/flag-icon.css') }}">
    <!-- Feather icon-->
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-asset/css/feather-icon.css') }}">
    <!-- Plugins css start-->
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-asset/css/sweetalert2.css') }}">
    <!-- Plugins css Ends-->
    <!-- Bootstrap css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-asset/css/bootstrap.css') }}">
    <!-- App css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-asset/css/style.css') }}">
    <link id="color" rel="stylesheet" href="{{ asset('admin-asset/css/color-1.css') }}" media="screen">
    <!-- Responsive css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-asset/css/responsive.css') }}">
  </head>
  <body>
    <!-- Loader starts-->
    <div class="loader-wrapper">
      <div class="theme-loader">
        <div class="loader-p"></div>
      </div>
    </div>
    <!-- Loader ends-->
    <!-- page-wrapper Start-->
    <section>
      <div class="container-fluid p-0" style="background-image: url('{{ asset('uploads/media/constant/forgot-password.jpg') }}');">
        <div class="row m-0">
          <div class="col-12 p-0">
            <div class="login-card">
              <div class="login-main">
                <form class="theme-form login-form" action="{{ route('password.update') }}" method="POST">
                  <div class="mb-4 d-flex justify-content-center">
                    <img src="{{ asset('uploads/media/constant/icon.png') }}" alt="" style="width: 22.5%; margin: auto; border-radius: 25%;">
                  </div>
                  <div class="mb-3">
                    <h4 style="text-align: center;">{{__('auth_pages.reset_from_here')}}</h4>
                  </div>
                  @if (session('status'))
                      <div class="alert alert-success mb-5" role="alert">
                          {{ session('status') }}
                      </div>
                  @endif
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">
                    <div class="form-group">
                        <label>{{__('auth_pages.enter_mail')}}</label>
                        <div class="form-group">
                        <div class="input-group"><span class="input-group-text"><i class="icon-email"></i></span>
                            <input class="form-control" type="email" id="email" name="email" placeholder="example@gmail.com" value="{{ $email ?? old('email') }}" required="" autocomplete="email" autofocus>
                        </div>
                        </div>
                    </div>
                    <div class="form-group">
                      <label>{{__('auth_pages.new_pwd')}}</label>
                      <div class="input-group"><span class="input-group-text"><i class="icon-lock"></i></span>
                        <input class="form-control" type="password" name="password" id="password" required="" placeholder="*********">
                      </div>
                    </div>
                    <div class="form-group">
                      <label>{{__('auth_pages.new_pwd_repeat')}}</label>
                      <div class="input-group"><span class="input-group-text"><i class="icon-lock"></i></span>
                        <input class="form-control" type="password" name="password-confirm" id="password-confirm" required="" placeholder="*********">
                      </div>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-primary btn-block" type="submit">{{__('auth_pages.reset_the_password')}}</button>
                    </div>
                    <p>{{__('auth_pages.already_have_password')}}<a class="ms-2" href="{{ route('login') }}">{{__('auth_pages.login')}} </a></p>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- page-wrapper end-->
    <!-- latest jquery-->
    <script src="{{ asset('admin-asset/js/jquery-3.5.1.min.js') }}"></script>
    <!-- feather icon js-->
    <script src="{{ asset('admin-asset/js/icons/feather-icon/feather.min.js') }}"></script>
    <script src="{{ asset('admin-asset/js/icons/feather-icon/feather-icon.js') }}"></script>
    <!-- Sidebar jquery-->
    <script src="{{ asset('admin-asset/js/sidebar-menu.js') }}"></script>
    <script src="{{ asset('admin-asset/js/config.js') }}"></script>
    <!-- Bootstrap js-->
    <script src="{{ asset('admin-asset/js/bootstrap/popper.min.js') }}"></script>
    <script src="{{ asset('admin-asset/js/bootstrap/bootstrap.min.js') }}"></script>
    <!-- Plugins JS start-->
    <script src="{{ asset('admin-asset/js/sweet-alert/sweetalert.min.js') }}"></script>
    <!-- Plugins JS Ends-->
    <!-- Theme js-->
    <script src="{{ asset('admin-asset/js/script.js') }}"></script>
    <!-- login js-->
    <!-- Plugin used-->
  </body>
</html>
