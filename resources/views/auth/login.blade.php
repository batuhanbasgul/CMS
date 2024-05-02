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
    <title>{{__('auth_pages.login')}} </title>
    <!-- Google font-->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&amp;display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&amp;display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,400;0,500;0,600;0,700;0,800;0,900;1,300;1,400;1,500;1,600;1,700;1,800;1,900&amp;display=swap" rel="stylesheet">
    <!-- Font Awesome-->
    <link rel="stylesheet" type="text/css" href="{{ 'admin-asset/css/fontawesome.css' }}">
    <!-- ico-font-->
    <link rel="stylesheet" type="text/css" href="{{ 'admin-asset/css/icofont.css' }}">
    <!-- Themify icon-->
    <link rel="stylesheet" type="text/css" href="{{ 'admin-asset/css/themify.css' }}">
    <!-- Flag icon-->
    <link rel="stylesheet" type="text/css" href="{{ 'admin-asset/css/flag-icon.css' }}">
    <!-- Feather icon-->
    <link rel="stylesheet" type="text/css" href="{{ 'admin-asset/css/feather-icon.css' }}">
    <!-- Plugins css start-->
    <!-- Plugins css Ends-->
    <!-- Bootstrap css-->
    <link rel="stylesheet" type="text/css" href="{{ 'admin-asset/css/bootstrap.css' }}">
    <!-- App css-->
    <link rel="stylesheet" type="text/css" href="{{ 'admin-asset/css/style.css' }}">
    <link id="color" rel="stylesheet" href="{{ 'admin-asset/css/color-1.css' }}" media="screen">
    <!-- Responsive css-->
    <link rel="stylesheet" type="text/css" href="{{ 'admin-asset/css/responsive.css' }}">
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
      <div class="container-fluid">
        <div class="row">
          <div class="col-xl-5"><img class="bg-img-cover bg-center" src="{{ asset('uploads/media/constant/login.jpg') }}" alt="looginpage"></div>
          <div class="col-xl-7 p-0">
            <div class="login-card">
              <form class="theme-form login-form" action="{{ route('login') }}" method="POST">
                @csrf
                <div class="mb-4 d-flex justify-content-center">
                  <img src="{{ asset('uploads/media/constant/icon.png') }}" alt="" style="width: 22.5%; margin: auto; border-radius: 25%;">
                </div>
                <h4 class="mb-2">{{__('auth_pages.login')}}</h4>
                <h6>{{__('auth_pages.welcome_login')}}</h6>
                @error('email')
                <div class="alert alert-danger mb-5" role="alert">
                  {{__('auth_pages.invalid_mail_or_pwd')}}
                </div>
                @enderror
                <div class="form-group">
                  <label>{{__('auth_pages.email')}}</label>
                  <div class="input-group"><span class="input-group-text"><i class="icon-email"></i></span>
                    <input class="form-control" type="email" required="" id="login-username" name="email" placeholder="example@gmail.com" autocomplete="email" autofocus value="admin@demo.com">
                  </div>
                </div>
                <div class="form-group">
                  <label>{{__('auth_pages.pwd')}}</label>
                  <div class="input-group"><span class="input-group-text"><i class="icon-lock"></i></span>
                    <input class="form-control" type="password" id="login-password" name="password" required="" placeholder="*********" autocomplete="current-password" value="demodemo">
                  </div>
                </div>
                <div class="form-group">
                  <div class="checkbox">
                    <input id="checkbox1" type="checkbox">
                  </div><a class="link" href="{{ route('password.request') }}">{{__('auth_pages.forgot_pwd')}}</a>
                </div>
                <div class="form-group">
                    <button class="btn btn-primary btn-block" type="submit" style="width: 100%;">{{__('auth_pages.login')}}</button>
                </div>
                <!--
                <div class="login-social-title">
                  <h5>Sosyal Medya</h5>
                </div>
                <div class="form-group">
                  <ul class="login-social">
                    <li><a href="https://www.linkedin.com/login" target="_blank"><i data-feather="linkedin"></i></a></li>
                    <li><a href="https://www.linkedin.com/login" target="_blank"><i data-feather="twitter"></i></a></li>
                    <li><a href="https://www.linkedin.com/login" target="_blank"><i data-feather="facebook"></i></a></li>
                    <li><a href="https://www.instagram.com/login" target="_blank"><i data-feather="instagram">                  </i></a></li>
                  </ul>
                </div>
                -->
                <!--<p>Don't have account?<a class="ms-2" href="{{ route('register') }}">Create Account</a></p>-->
              </form>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- page-wrapper end-->
    <!-- latest jquery-->
    <script src="{{ 'admin-asset/js/jquery-3.5.1.min.js' }}"></script>
    <!-- feather icon js-->
    <script src="{{ 'admin-asset/js/icons/feather-icon/feather.min.js' }}"></script>
    <script src="{{ 'admin-asset/js/icons/feather-icon/feather-icon.js' }}"></script>
    <!-- Sidebar jquery-->
    <script src="{{ 'admin-asset/js/sidebar-menu.js' }}"></script>
    <script src="{{ 'admin-asset/js/config.js' }}"></script>
    <!-- Bootstrap js-->
    <script src="{{ 'admin-asset/js/bootstrap/popper.min.js' }}"></script>
    <script src="{{ 'admin-asset/js/bootstrap/bootstrap.min.js' }}"></script>
    <!-- Plugins JS start-->
    <!-- Plugins JS Ends-->
    <!-- Theme js-->
    <script src="{{ 'admin-asset/js/script.js' }}"></script>
    <!-- login js-->
    <!-- Plugin used-->
  </body>
</html>
