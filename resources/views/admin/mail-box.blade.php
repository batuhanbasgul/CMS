@extends('admin.layouts.base')

@section('title',__('admin_mailbox.mails'))

@section('content')
<!-- Main Container -->
<div class="page-body pt-5">
  <div class="container-fluid">
    <div class="page-header">
      <div class="row">
        <div class="col-7">
          <h3>Mesajlar
            @if (session('unread_mails'))
              <span class="badge rounded-pill bg-primary m-2">
                {{ count(session('unread_mails')) }}
              </span>
            @endif
          </h3>
          <ol class="breadcrumb">
            <li class="breadcrumb-item disappear-500"><a href="{{ route('admin.index') }}"><ins>{{__('admin_mailbox.main_page')}}</ins></a></li>
            <li class="breadcrumb-item disappear-500">{{__('admin_mailbox.mails')}}</li>
            <li class="breadcrumb-item disappear-500">{{__('admin_mailbox.view_mails')}}</li>
          </ol>
        </div>
        <div class="col-5 d-flex justify-content-end">
            <a href="{{ route('admin.mail-box.index') }}">
              <button type="button" class="btn btn-primary btn-sm m-1">
                <i class="fas fa-mail-bulk text-light"></i>
                <span class="d-none d-sm-inline ms-1">{{__('admin_mailbox.all_mails')}} <small>({{ $mail_count }})</small> </span>
              </button>
            </a>
            <a href="{{ route('admin.mail-box.index',['filter' => 'read']) }}">
              <button type="button" class="btn btn-primary btn-sm m-1">
                <i class="fas fa-envelope-open text-light"></i>
                <span class="d-none d-sm-inline ms-1">{{__('admin_mailbox.read')}} <small>({{ $read_count }})</small> </span>
              </button>
            </a>
            <a href="{{ route('admin.mail-box.index',['filter' => 'unread']) }}">
              <button type="button" class="btn btn-primary btn-sm m-1">
                <i class="fas fa-envelope text-light"></i>
                <span class="d-none d-sm-inline ms-1">{{__('admin_mailbox.unread')}} <small>({{ $unread_count }})</small> </span>
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
          <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-body">
                  <div class="dt-ext table-responsive p-2 mt-0">
                    <table class="display" id="responsive">
                      <thead>
                        <tr>
                          <th>{{__('admin_mailbox.sender')}}</th>
                          <th>{{__('admin_mailbox.subject')}}</th>
                          <th>{{__('admin_mailbox.status')}}</th>
                          <th>{{__('admin_mailbox.time')}}</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($mails as $mail)
                        <tr class="custom-table">
                            <td onclick="window.location='{{ route('admin.mail-box.show', [$mail->id]) }}';">
                                <p class="fs-sm fw-medium text-bold mb-0">{{ $mail->name }}</p>
                                <p class="fs-sm fw-medium text-muted mb-0">{{ $mail->email }}</p>
                            </td>
                            <td onclick="window.location='{{ route('admin.mail-box.show', [$mail->id]) }}';">
                                <a class="fw-semibold" href="{{ route('admin.mail-box.show', [$mail->id]) }}" >{{ $mail->subject }}</a>
                                <p class="fs-sm fw-medium text-muted mb-0">
                                @if (strlen($mail->context) > 50)
                                  {{ substr($mail->context,0,50) }}...
                                @else
                                  {{ $mail->context }}
                                @endif
                            </td>
                            <td onclick="window.location='{{ route('admin.mail-box.show', [$mail->id]) }}';">
                                @if ($mail->is_read)
                                  <span class="fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill bg-success-light text-success">{{__('admin_mailbox.read')}}</span>
                                @else
                                  <span class="fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill bg-danger-light text-danger">{{__('admin_mailbox.unread')}}</span>
                                @endif
                            </td>
                            <td onclick="window.location='{{ route('admin.mail-box.show', [$mail->id]) }}';">
                                <em class="disappear-500">{{ $mail->created_at->diffForHumans() }}</em>
                                <p class="fs-sm fw-medium text-muted mb-0">{{ date('d-m-Y', strtotime($mail->created_at)) }}</p>
                            </td>
                        </tr>
                        @endforeach
                      </tbody>
                      <tfoot>
                        <tr>
                          <th>{{__('admin_mailbox.sender')}}</th>
                          <th>{{__('admin_mailbox.subject')}}</th>
                          <th>{{__('admin_mailbox.status')}}</th>
                          <th>{{__('admin_mailbox.time')}}</th>
                        </tr>
                      </tfoot>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
  </div>
  <!-- Container-fluid Ends-->
</div>

@endsection
