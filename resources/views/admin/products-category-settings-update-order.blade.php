@extends('admin.layouts.base')

@section('title',__('admin_products_category.order_categories'))

@section('content')
<!-- Main Container -->
<div class="page-body pt-5">
  <div class="container-fluid">
    <div class="page-header">
      <div class="row">
        <div class="col-sm-7">
          <div class="row">
            <div class="col-7">
                <h3>{{__('admin_products_category.order_categories')}} @if(count($langs) != 1)<span class="title-lang-code">({{ request('lang_code') }})</span>@endif</h3>
                <ol class="breadcrumb">
                  <li class="breadcrumb-item disappear-500"><a href="{{ route('admin.index') }}"><ins> {{__('admin_products_category.main_page')}}</ins></a></li>
                  <li class="breadcrumb-item disappear-500">{{__('admin_products_category.categories')}}</li>
                  <li class="breadcrumb-item disappear-500">{{__('admin_products_category.order_categories')}}</li>
                </ol>
            </div>
            <div class="col-5">
                <!--
                  Refreshing page with selected language code.
                -->
                @if(count($langs) != 1)
                  @foreach ($langs as $lang)
                    @if (request('lang_code')==$lang->lang_code)
                    <div class="mx-2 d-none">
                      <span class="disabled disappear-1250">({{__('admin_products_category.current_language')}})</span>
                      <img src="{{ asset($lang->icon) }}" alt="">
                    </div>
                    @endif
                  @endforeach
                  <select class="form-select" id="lang-select" name="lang-select" onchange="window.location.href=this.options[this.selectedIndex].value;">
                      @foreach ($langs as $lang)
                      @if ($lang->is_active)
                      <option value="{{ route('admin.products-category-settings.edit-order', ['lang_code' => $lang->lang_code]) }}"
                        @if (request('lang_code')==$lang->lang_code)
                            selected
                        @endif
                        >{{ $lang->lang_name }}</option>
                        @endif
                      @endforeach
                  </select>
                @endif
                </div>
          </div>
        </div>
        <div class="col-5 d-flex justify-content-end">
            <a href="{{ route('admin.products-category-settings.index', ['lang_code' => request('lang_code')]) }}">
              <button type="button" class="btn btn-primary btn-sm m-1">
                  <i class="me-2 fa-solid fa-angles-left"></i><span class="disappear-500">{{__('admin_products_category.turn_back')}}</span>
              </button>
            </a>
        </div>
      </div>
    </div>
  </div>
  <!-- Sweet Alert -->
  @if(session('success'))
  <script>swal("{{__('admin_products_category.success')}}", "{{__('admin_products_category.success_process')}}", "success");</script>
  @elseif(session('error'))
  <script>swal("{{__('admin_products_category.error')}}", "{{__('admin_products_category.error_process')}}", "error");</script>
  @endif
  <!-- Container-fluid starts-->
  <div class="container-fluid">
    <div class="card">
        <menu class="ms-4 my-0" id="nestable-menu">
            <div class="row">
                <div class="col-12 col-lg-6 d-flex justify-content-start">
                    <button type="button" class="btn btn-primary btn-sm m-1 mt-4" data-action="expand-all">
                      <i class="fas fa-plus me-2"></i> {{__('admin_products_category.expand')}}
                    </button>
                    <button type="button" class="btn btn-primary btn-sm m-1 mt-4" data-action="collapse-all">
                      <i class="fas fa-minus me-2"></i> {{__('admin_products_category.collapse')}}
                    </button>
                    <a href="{{ route('admin.app-card-settings.index', ['refresh' => true]) }}">
                      <button type="button" class="btn btn-primary btn-sm m-1 mt-4">
                        <i class="fas fa-redo me-2"></i> {{__('admin_products_category.reset')}}
                      </button>
                    </a></div>
            </div>
        </menu>
        <div class="card-body">
            <form action="{{ route('admin.products-category-settings.update',[$categories[0]->id]) }}" method="POST">
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
                    @foreach ($categories as $a)
                    @if($a->parent_category_uid == '0')
                    <li class="dd-item" data-id="{{ $a->id }}">
                        <div class=" dd-handle " @if(!$a->is_active) style="background: #BBB;" @endif>
                          {{ $a->name }}
                        </div>
                        <ol class="dd-list">
                          @foreach ($categories as $b)
                          @if($b->parent_category_uid == $a->uid)
                          <li class="dd-item" data-id="{{ $b->id }}">
                            <div class="dd-handle" @if(!$b->is_active) style="background: #BBB;" @endif>
                              {{ $b->name }}
                            </div>
                              <ol class="dd-list">
                                @foreach ($categories as $c)
                                @if($c->parent_category_uid == $b->uid)
                                <li class="dd-item" data-id="{{ $c->id }}">
                                  <div class="dd-handle" @if(!$c->is_active) style="background: #BBB;" @endif>
                                    {{ $c->name }}
                                  </div>
                                  <ol class="dd-list">
                                    @foreach ($categories as $d)
                                    @if($d->parent_category_uid == $c->uid)
                                    <li class="dd-item" data-id="{{ $d->id }}">
                                      <div class="dd-handle" @if(!$d->is_active) style="background: #BBB;" @endif>
                                        {{ $d->name }}
                                      </div>
                                      <ol class="dd-list">
                                        @foreach ($categories as $e)
                                        @if($e->parent_category_uid == $d->uid)
                                        <li class="dd-item" data-id="{{ $e->id }}">
                                          <div class="dd-handle" @if(!$e->is_active) style="background: #BBB;" @endif>
                                            {{ $e->name }}
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
                        <input style="width:100%;" type="submit" class="btn btn btn-primary my-2 mx-1" id="updatecategoryorder" name="updatecategoryorder" value="{{__('admin_products_category.save_order')}}">
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






