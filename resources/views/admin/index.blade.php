@extends('admin.layouts.base')

@section('title',__('admin_main.main_page'))

@section('content')

<div class="page-body pt-5">
  <!-- Container-fluid starts-->
  <div class="container-fluid dashboard-default-sec">
    <div class="row">

      @can('master', Auth::id())
        @if ($construction && $construction->is_active)
        <div class="alert alert-danger d-flex align-items-center justify-content-between" role="alert">
          <div class="flex-grow-1 me-3">
            <p class="mb-0">
            {{__('admin_main.construction')}} <a class="alert-link" style="color: whitesmoke" href="{{ route('admin.construction.index', ['page' => 2, 'lang_code'=>App::getLocale()]) }}">{{__('admin_main.change')}}</a>
            </p>
          </div>
          <div class="flex-shrink-0">
            <i class="fa fa-fw fa-exclamation-circle"></i>
          </div>
        </div>
        @endif
      @endcan
      @if ($maintenance->maintenance_app)
      <div class="alert alert-danger d-flex align-items-center justify-content-between" role="alert">
        <div class="flex-grow-1 me-3">
          <p class="mb-0">
            {{__('admin_main.site_maintenance')}} <a class="alert-link" style="color: whitesmoke" href="{{ route('admin.settings.edit',['user_id' => Auth::id(),'setting' => 'maintenance', 'lang_code'=>App::getLocale()]) }}">{{__('admin_main.change')}}</a>
          </p>
        </div>
        <div class="flex-shrink-0">
          <i class="fa fa-fw fa-exclamation-circle"></i>
        </div>
      </div>
      @endif
      @if ($maintenance->maintenance_panel)
      <div class="alert alert-danger d-flex align-items-center justify-content-between" role="alert">
        <div class="flex-grow-1 me-3">
          <p class="mb-0">
            {{__('admin_main.panel_maintenance')}} <a class="alert-link" style="color: whitesmoke" href="{{ route('admin.settings.edit',['user_id' => Auth::id(),'setting' => 'maintenance']) }}">{{__('admin_main.change')}}</a>
          </p>
        </div>
        <div class="flex-shrink-0">
          <i class="fa fa-fw fa-exclamation-circle"></i>
        </div>
      </div>
      @endif

      <!-- Welcome & Views -->
      <div class="col-xl-6 box-col-12 des-xl-100">
        <div class="row">
          <!-- Welcome Card -->
          <div class="col-12">
            <div class="card profile-greeting" style="background-image: none;">
              <div class="card-header">
                <div class="header-top">
                </div>
              </div>
              <div class="card-body text-center p-t-0">
                <h3 class="font-primary">{{__('admin_main.welcome')}} @if($user_name != null){{ $user_name }}@endif!!</h3>
                <p class="font-dark">
                    {{__('admin_main.welcome_context')}}
                </p>
              </div>
              <div class="confetti">
                <div class="confetti-piece"></div>
                <div class="confetti-piece"></div>
                <div class="confetti-piece"></div>
                <div class="confetti-piece"></div>
                <div class="confetti-piece"></div>
                <div class="confetti-piece"></div>
                <div class="confetti-piece"></div>
                <div class="confetti-piece"></div>
                <div class="confetti-piece"></div>
                <div class="confetti-piece"></div>
                <div class="confetti-piece"></div>
                <div class="confetti-piece"></div>
                <div class="confetti-piece"></div>
              </div>
            </div>
          </div>
          <!-- View Cards -->
          <div class="col-xl-6 col-md-3 col-sm-6 box-col-3 des-xl-25 rate-sec">
            <div class="card income-card card-primary">
              <div class="card-body text-center">
                <div class="round-box">
                  <i class="fas fa-users"></i>
                </div>
                <h5>{{ $views['sum'] }}</h5>
                <p>{{__('admin_main.view_sum')}}</p><a class="btn-arrow arrow-primary" href="javascript:void(0)"></a>
              </div>
            </div>
          </div>
          <div class="col-xl-6 col-md-3 col-sm-6 box-col-3 des-xl-25 rate-sec">
            <div class="card income-card card-primary">
              <div class="card-body text-center">
                <div class="round-box">
                  <i class="fas fa-user-friends"></i>
                </div>
                <h5>{{ $views['monthly'] }}</h5>
                <p>{{__('admin_main.view_month')}}</p><a class="btn-arrow arrow-primary" href="javascript:void(0)"></a>
              </div>
            </div>
          </div>
          <div class="col-xl-6 col-md-3 col-sm-6 box-col-3 des-xl-25 rate-sec">
            <div class="card income-card card-primary">
              <div class="card-body text-center">
                <div class="round-box">
                  <i class="fas fa-user-alt"></i>
                </div>
                <h5>{{ $views['weekly'] }}</h5>
                <p>{{__('admin_main.view_week')}}</p><a class="btn-arrow arrow-primary" href="javascript:void(0)"></a>
              </div>
            </div>
          </div>
          <div class="col-xl-6 col-md-3 col-sm-6 box-col-3 des-xl-25 rate-sec">
            <div class="card income-card card-primary">
              <div class="card-body text-center">
                <div class="round-box">
                  <i class="far fa-eye"></i>
                </div>
                <h5>{{ $views['daily'] }}</h5>
                <p>{{__('admin_main.view_day')}}</p><a class="btn-arrow arrow-primary" href="javascript:void(0)"></a>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Messages Income -->
      <div class="col-xl-6 box-col-12 des-xl-100">
        <div class="email-wrap">
          <div class="email-right-aside">
            <div class="card email-body">
              <div class="email-profile">
                <div>
                  <div class="pe-0 b-r-light"></div>
                  <div class="email-top">
                    <div class="row">
                      <div class="col-6">
                        <h3 class="font-primary">{{__('admin_main.mail_box')}}</h3>
                      </div>
                      <div class="col-6 d-flex justify-content-end">
                        <a href="{{ route('admin.mail-box.index') }}">
                          <button type="button" class="btn btn-primary">
                            <i class="fas fa-mail-bulk me-1"></i> <span class="disappear-500">{{__('admin_main.go_messages')}}</span>
                          </button>
                        </a>
                      </div>
                    </div>
                  </div>
                  <div class="inbox">
                    @if (session('unread_mails'))
                      @foreach (session('unread_mails') as $mail)
                      <a href="{{ route('admin.mail-box.show', [$mail->id]) }}">
                        <div class="media">
                          <div class="media-body">
                            <h6>{{ $mail->name }} </h6>
                            <p>{{ $mail->subject }}</p><span>{{ $mail->created_at->diffForHumans() }}</span>
                          </div>
                        </div>
                      </a>
                      <a href="{{ route('admin.mail-box.index') }}">
                        <div class="media ">
                          <div class="media-body d-flex justify-content-center">
                            <h6>{{__('admin_main.all_messages')}}</h6>
                          </div>
                        </div>
                      </a>
                      @endforeach
                    @else
                    <div class="media">
                      <div class="media-body">
                        <h6>{{__('admin_main.no_message')}}</h6>
                      </div>
                    </div>
                    @endif
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Container-fluid Ends-->
</div>
@endsection
