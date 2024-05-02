@extends('admin.layouts.base')

@section('title',__('admin_mailbox.view_mail'))

@section('content')
<!-- Main Container -->
<div class="page-body pt-5">
  <div class="container-fluid">
    <div class="page-header">
      <div class="row">
        <div class="col-7">
          <h3> <strong>{{ $mail->name }}</strong> - {{ $mail->subject }}</h3>
          <ol class="breadcrumb">
            <li class="breadcrumb-item disappear-500"><a href="{{ route('admin.index') }}"><ins>{{__('admin_mailbox.main_page')}}</ins></a></li>
            <li class="breadcrumb-item disappear-500">{{__('admin_mailbox.mails')}}</li>
            <li class="breadcrumb-item disappear-500">{{__('admin_mailbox.view_mail')}}</li>
          </ol>
        </div>
        <div class="col-5 d-flex justify-content-end">
            <a href="{{ route('admin.mail-box.index') }}">
              <button type="button" class="btn btn-primary">
                <i class="fas fa-arrow-alt-circle-left"></i><span class="disappear-800"> {{__('admin_mailbox.turn_back')}}</span>
              </button>
            </a>
        </div>
      </div>
    </div>
  </div>
  <!-- Sweet Alert -->
  @if(session('success'))
  <script>swal("{{__('admin_mailbox.success')}}", "{{__('admin_mailbox.success_process')}}", "success");</script>
  @elseif(session('error'))
  <script>swal("{{__('admin_mailbox.error')}}", "{{__('admin_mailbox.error_process')}}", "error");</script>
  @endif
  <!-- Container-fluid starts-->
  <div class="container-fluid">
    <div class="card">
      <div class="card-body">
        <div class="row mt-1">
          <div class="col-lg-3">
            <p class="fs-sm text-start text-muted mb-1">
              <span style="font-weight: 800; font-size: 1rem;">{{__('admin_mailbox.sent_date')}}</span>
            </p>
            <p class="fs-sm text-muted">
              {{ date('d-m-Y', strtotime($mail->created_at)) }} -- {{ date('h:m:s', strtotime($mail->created_at)) }}
            </p>
          </div>
         <div class="col-lg-9">
            <div class="row">
              <div class="col-6">
                <p class="fs-sm text-start text-muted">
                  <span style="font-weight: 800; font-size: 1rem;">{{__('admin_mailbox.sender')}} :</span> {{ $mail->name }}
                </p>
              </div>
              <div class="col-6">
                <p class="fs-sm text-muted text-end">
                  {{ $mail->created_at->diffForHumans() }}
                </p>
              </div>
            </div>
            <div class="mb-4">
               <p class="fs-sm text-muted">
                <span style="font-weight: 800; font-size: 1rem;">{{__('admin_mailbox.subject')}} :</span> {{ $mail->subject }}
              </p>
            </div>
            <div class="mb-4">
               <p class="fs-sm text-muted">
                {{ $mail->context }}
              </p>
            </div>
          </div>
        </div>
        <div class="row">
            <div class="col d-flex justify-content-end">
                <form action="{{ route('admin.mail-box.destroy',[$mail->id]) }}" method="POST" onclick="return confirm('{{__('admin_mailbox.delete_question')}}')">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="turn_index" value=true>
                    <button type="submit" class="btn btn-danger mx-auto">
                      <i class="fa fa-fw fa-times me-1"></i> {{__('admin_mailbox.delete')}}
                    </button>
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






