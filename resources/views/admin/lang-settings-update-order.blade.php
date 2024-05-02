@extends('admin.layouts.base')

@section('title',__('admin_lang.order_content'))

@section('content')
<!-- Main Container -->
<div class="page-body pt-5">
  <div class="container-fluid">
    <div class="page-header">
      <div class="row">
        <div class="col-sm-7">
          <h3>{{__('admin_lang.update_language_order')}} </h3>
          <ol class="breadcrumb">
            <li class="breadcrumb-item disappear-500"><a href="{{ route('admin.index') }}"><ins>{{__('admin_lang.main_page')}}</ins></a></li>
            <li class="breadcrumb-item disappear-500">{{__('admin_lang.languages')}}</li>
            <li class="breadcrumb-item disappear-500">{{__('admin_lang.update_language_order')}}</li>
          </ol>
        </div>
        <div class="col-5 d-flex justify-content-end">
            <a href="{{ route('admin.lang-settings.index') }}" >
              <button type="button" class="btn btn-primary btn-sm m-1">
                <i class="me-2 fa-solid fa-angles-left"></i><span>{{__('admin_lang.turn_back')}}</span>
              </button>
            </a>
        </div>
      </div>
    </div>
  </div>
  <!-- Sweet Alert -->
  @if(session('success'))
  <script>swal("{{__('admin_lang.success')}}", "{{__('admin_lang.success_process')}}", "success");</script>
  @elseif(session('error'))
  <script>swal("{{__('admin_lang.error')}}", "{{__('admin_lang.success_process')}}", "error");</script>
  @endif
  <!-- Container-fluid starts-->
  <div class="container-fluid">
    <div class="card">
        <menu class="ms-4 my-0" id="nestable-menu">
            <a href="{{ route('admin.lang-settings.index', ['refresh' => true]) }}">
              <button type="button" class="btn btn-primary btn-sm m-1 mt-4" style="width:128px;">
                <i class="fas fa-redo me-2"></i> {{__('admin_lang.reset')}}
              </button>
            </a>
        </menu>
        <div class="card-body">
            <form action="{{ route('admin.lang-settings.update',[$langs[0]->id]) }}" method="POST">
              @csrf
              @method('PUT')
              <!--
                  Nestable max depth
              -->
              <input type="hidden" id="maxDepth" value="1"></input>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-vcenter mb-0">
                    <thead>
                        <tr>
                        <th class="d-none d-sm-table-cell text-center" style="width: 8.2%;">{{__('admin_lang.order')}}</th>
                        <th class="d-none d-sm-table-cell" style="width: 91.7%;">{{__('admin_lang.content')}}</th>
                        </tr>
                    </thead>
                    </table>
                    <!-- NESTABLE START-->
                    <div class="row m-0" style="overflow: hidden">
                        <div class="col-2 col-md-1 text-center px-0">
                        @for ($i=1;$i<=count($langs);$i++)
                            <div class="dd-item">
                                <div class="text-center dd-handle-disabled" style="font-size: 1rem;"> {{ $i }} </div>
                            </div>
                        @endfor
                        </div>
                        <div class="col-10 col-md-11 px-0">
                            <div class="dd" id="nestable" style=" width:100%;">
                                <ol class="dd-list">
                                    @foreach ($langs as $item)
                                    <li class="dd-item" data-id="{{ $item->id }}">
                                        <div class="dd-handle" @if(!$item->is_active) style="background: #BBB;" @endif>
                                        {{ $item->lang_name }}
                                        </div>
                                    </li>
                                    @endforeach
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
                <input type="hidden" id="nestable-output" name="nestable_output"></input>
                <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
                <script>
                  $(document).ready(function()
                  {

                      var updateOutput = function(e)
                      {
                          var list   = e.length ? e : $(e.target),
                              output = list.data('output');
                          if (window.JSON) {
                              output.val(window.JSON.stringify(list.nestable('serialize')));//, null, 2));
                          } else {
                              output.val('JSON browser support required for this demo.');
                          }
                      };

                      // activate Nestable for list 1
                      $('#nestable').nestable({
                          group: 1
                      })
                      .on('change', updateOutput);

                      // output initial serialised data
                      updateOutput($('#nestable').data('output', $('#nestable-output')));
                  });
                </script>
                <!-- NESTABLE END-->
              <div class="mb-4">
                <div class="row mx-0">
                  <div class="col-md-6 col-lg-8 col-xl-8 col-xxl-9"></div>
                  <div class="col-md-6 col-lg-4 col-xl-4 col-xxl-3">
                    <div class="input-group" >
                      <small class="font-weight-bold ms-2">{{__('admin_lang.drag_to_order')}}</small>
                      <input style="width:100%;" type="submit" class="btn btn btn-primary my-2 mx-1" id="updatelangssorder" name="updatelangssorder" value="{{__('admin_lang.save_order')}}">
                    </div>
                  </div>
                </div>
              </div>
            </form>
        </div>
    </div>
  </div>
  <!-- Container-fluid Ends-->
</div>
<!-- END Main Container -->
@endsection






