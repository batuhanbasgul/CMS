@extends('admin.layouts.base')

@section('title',__('admin_menu.order_content'))

@section('content')
<!-- Main Container -->
<div class="page-body pt-5">
  <div class="container-fluid">
    <div class="page-header">
      <div class="row">
        <div class="col-sm-7">
          <h3>{{__('admin_menu.update_menu_order')}} @if(count($langs) != 1)<span class="title-lang-code">({{ request('lang_code') }})</span>@endif</h3>
          <ol class="breadcrumb">
            <li class="breadcrumb-item disappear-500"><a href="{{ route('admin.index') }}"><ins>{{__('admin_menu.main_page')}}</ins></a></li>
            <li class="breadcrumb-item disappear-500">{{__('admin_menu.menus')}}</li>
            <li class="breadcrumb-item disappear-500">{{__('admin_menu.update_menu_order')}}</li>
          </ol>
        </div>
        <div class="col-5 d-flex justify-content-end">
            <a href="{{ route('admin.menu-settings.index', ['lang_code' => request('lang_code')]) }}">
              <button type="button" class="btn btn-primary btn-sm m-1">
                  <i class="me-2 fa-solid fa-angles-left"></i><span class="disappear-500">{{__('admin_menu.turn_back')}}</span>
              </button>
            </a>
        </div>
      </div>
    </div>
  </div>
  <!-- Sweet Alert -->
  @if(session('success'))
  <script>swal("{{__('admin_menu.success')}}", "{{__('admin_menu.success_process')}}", "success");</script>
  @elseif(session('error'))
  <script>swal("{{__('admin_menu.error')}}", "{{__('admin_menu.error_process')}}", "error");</script>
  @endif
  <!-- Container-fluid starts-->
  <div class="container-fluid">
    <div class="card">
        <menu class="ms-4 my-0" id="nestable-menu">
            <div class="row">
                <div class="col-12 col-lg-6 d-flex justify-content-start">
                    <button type="button" class="btn btn-primary btn-sm m-1 mt-4" data-action="expand-all">
                      <i class="me-2 fa-solid fa-plus"></i> {{__('admin_menu.expand')}}
                    </button>
                    <button type="button" class="btn btn-primary btn-sm m-1 mt-4" data-action="collapse-all">
                        <i class="me-2 fa-solid fa-minus"></i></i> {{__('admin_menu.collapse')}}
                    </button>
                    <a href="{{ route('admin.app-card-settings.index', ['refresh' => true]) }}">
                      <button type="button" class="btn btn-primary btn-sm m-1 mt-4">
                        <i class="fas fa-redo me-2"></i> {{__('admin_menu.reset')}}
                      </button>
                    </a></div>
            </div>
        </menu>
        <div class="card-body">
            <form action="{{ route('admin.menu-settings.update',[$menus[0]->id]) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
              <div class="col-12">
                <!--
                    Nestable maksimum derinlik
                -->
                <input type="hidden" id="maxDepth" value="3"></input>
                <!-- NESTABLE START-->
                <div class="dd" id="nestable" style="width: 100%">
                  <ol class="dd-list">
                    @foreach ($menus as $a)
                    @if($a->parent_menu_uid == '0')
                    <li class="dd-item" data-id="{{ $a->id }}">
                        <div class=" dd-handle " @if(!$a->is_desktop_active) style="background: #BBB;" @endif>
                          {{ $a->menu_name }}
                        </div>
                        <ol class="dd-list">
                          @foreach ($menus as $b)
                          @if($b->parent_menu_uid == $a->uid)
                          <li class="dd-item" data-id="{{ $b->id }}">
                            <div class="dd-handle" @if(!$b->is_desktop_active) style="background: #BBB;" @endif>
                              {{ $b->menu_name }}
                            </div>
                              <ol class="dd-list">
                                @foreach ($menus as $c)
                                @if($c->parent_menu_uid == $b->uid)
                                <li class="dd-item" data-id="{{ $c->id }}">
                                  <div class="dd-handle" @if(!$c->is_desktop_active) style="background: #BBB;" @endif>
                                    {{ $c->menu_name }}
                                  </div>
                                  <ol class="dd-list">
                                    @foreach ($menus as $d)
                                    @if($d->parent_menu_uid == $c->uid)
                                    <li class="dd-item" data-id="{{ $d->id }}">
                                      <div class="dd-handle" @if(!$d->is_desktop_active) style="background: #BBB;" @endif>
                                        {{ $d->menu_name }}
                                      </div>
                                      <ol class="dd-list">
                                        @foreach ($menus as $e)
                                        @if($e->parent_menu_uid == $d->uid)
                                        <li class="dd-item" data-id="{{ $e->id }}">
                                          <div class="dd-handle" @if(!$e->is_desktop_active) style="background: #BBB;" @endif>
                                            {{ $e->menu_name }}
                                          </div>
                                        </li>
                                        @endif
                                        @endforeach
                                      </ol>
                                    </li>
                                    @endif
                                    @endforeach
                                  </ol>
                                </li>
                                @endif
                                @endforeach
                              </ol>
                          </li>
                          @endif
                          @endforeach
                        </ol>
                    </li>
                    @endif
                    @endforeach
                  </ol>
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
                      $('#nestable-menu').on('click', function(e)
                      {
                          var target = $(e.target),
                              action = target.data('action');
                          if (action === 'expand-all') {
                              $('.dd').nestable('expandAll');
                          }
                          if (action === 'collapse-all') {
                              $('.dd').nestable('collapseAll');
                          }
                      });
                      // output initial serialised data
                      updateOutput($('#nestable').data('output', $('#nestable-output')));
                      $('#nestable3').nestable();
                  });
                </script>
                <!-- NESTABLE END-->
              </div>
              <div class="col-12">
                <div class="my-4">
                  <div class="row mx-0">
                    <div class="col-md-6 col-lg-8 col-xl-8 col-xxl-9"></div>
                    <div class="col-md-6 col-lg-4 col-xl-4 col-xxl-3">
                      <div class="input-group" >
                        <small class="font-weight-bold ms-2">{{__('admin_menu.drag_to_order')}}</small>
                        <input style="width:100%;" type="submit" class="btn btn btn-primary my-2 mx-1" id="updatemenuorder" name="updatemenuorder" value="{{__('admin_menu.save_order')}}">
                      </div>
                    </div>
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






