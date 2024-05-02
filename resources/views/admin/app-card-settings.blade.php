@extends('admin.layouts.base')

@section('title',__('admin_cards.main_page_cards'))

@section('content')
<!-- Main Container -->
<div class="page-body pt-5">
  <div class="container-fluid">
    <div class="page-header">
      <div class="row">
        <div class="col-7">
            <div class="row">
                <div class="col-7">
                    <h3>{{__('admin_cards.main_page_cards')}} @if(count($langs) != 1)<span class="title-lang-code">({{ request('lang_code') }})</span>@endif</h3>
                    <ol class="breadcrumb">
                      <li class="breadcrumb-item disappear-500"><a href="{{ route('admin.index') }}"><ins>{{__('admin_blog.main_page')}}</ins></a></li>
                      <li class="breadcrumb-item disappear-500">{{__('admin_cards.main_page_cards')}}</li>
                      <li class="breadcrumb-item disappear-500">{{__('admin_cards.show_main_page_cards')}}</li>
                    </ol>
                </div>
                <div class="col-5">
                    <!--
                      Refreshing page with selected language code.
                    -->
                    @if(count($langs) != 1)
                      @foreach ($langs as $lang)
                        @if ($lang_code==$lang->lang_code)
                        <div class="mx-2 d-none">
                          <span class="disabled disappear-1250">({{__('admin_blog.current_language')}})</span>
                          <img src="{{ asset($lang->icon) }}" alt="">
                        </div>
                        @endif
                      @endforeach
                      <select class="form-select" id="lang-select" name="lang-select" onchange="window.location.href=this.options[this.selectedIndex].value;">
                          @foreach ($langs as $lang)
                          @if ($lang->is_active)
                          <option value="{{ route('admin.app-card-settings.index',['lang_code'=>$lang->lang_code, 'page'=>request('page')]) }}"
                            @if ($lang_code==$lang->lang_code)
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
          <!--
            App Cards order.
          -->
        <a href="{{ route('admin.app-card-settings.edit-order', ['lang_code' => request('lang_code')]) }}">
          <button type="button" class="btn btn-primary btn-sm m-1">
            <i class="fas fa-stream me-2"></i><span class="disappear-800">{{__('admin_cards.order_content')}}</span>
          </button>
        </a>
          <!--
            Add new.
          -->
        <a href="{{ route('admin.app-card-settings.create', ['lang_code' => request('lang_code')]) }}">
          <button type="button" class="btn btn-primary btn-sm m-1">
            <i class="fa fa-fw fa-plus me-1"></i><span class="disappear-800">{{__('admin_cards.add_new')}}</span>
          </button>
        </a>
        </div>
      </div>
    </div>
  </div>
<!-- Sweet Alert -->
@if(session('success'))
<script>swal("{{__('admin_blog.success')}}", "{{__('admin_cards.success_process')}}", "success");</script>
@elseif(session('error'))
<script>swal("{{__('admin_cards.error')}}", "{{__('admin_cards.error_process')}}", "error");</script>
@elseif (session('no_card'))
<script>swal("{{__('admin_cards.error')}}", "{{__('admin_cards.no_card')}}", "error")</script>
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
                          <th style="width: 5%;">{{__('admin_cards.table_order')}}</th>
                          <th style="width: 20%;">{{__('admin_cards.table_icon')}}</th>
                          <th style="width: 20%;">{{__('admin_cards.table_title')}}</th>
                          @desktop <th style="width: 10%;">{{__('admin_cards.table_description')}}</th> @enddesktop
                          <th style="width: 20%;">{{__('admin_cards.table_actions')}}</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($cards as $card)
                        <tr class="custom-table">
                          <td onclick="window.location='{{ route('admin.app-card-settings.edit', [$card->id, 'page' => 1, 'lang_code' => request('lang_code')]) }}';">
                            {{ $card->order }}
                          </td>
                          <td onclick="window.location='{{ route('admin.app-card-settings.edit', [$card->id, 'page' => 1, 'lang_code' => request('lang_code')]) }}';">
                            <div style="font-size: 2rem;"><i class="{!! $card->icon !!}"></i></div>
                          </td>
                          <td onclick="window.location='{{ route('admin.app-card-settings.edit', [$card->id, 'page' => 1, 'lang_code' => request('lang_code')]) }}';">
                            <a href="{{ route('admin.app-card-settings.edit',[$card->id]) }}">
                              {{ $card->title }}
                            </a>
                          </td>
                          @desktop
                          <td onclick="window.location='{{ route('admin.app-card-settings.edit', [$card->id, 'page' => 1, 'lang_code' => request('lang_code')]) }}';">
                            <div style="font-size: 2rem;">{!! $card->description !!}   </div>
                          </td>
                          @enddesktop
                          <td>
                            <div class="input-group">
                              <div class="col-6">
                                <a href="{{ route('admin.app-card-settings.edit',[$card->id, 'page' => 1, 'lang_code' => request('lang_code')]) }}">
                                  <button type="button" class="btn btn-sm btn-alt-primary mx-auto" style="width:90%">
                                    <i class="fas fa-pen me-1"></i><span class="disappear-1300">{{__('admin_cards.table_update')}}</span>
                                  </button>
                                </a>
                              </div>
                              <div class="col-6">
                                <form action="{{ route('admin.app-card-settings.destroy',[$card->id]) }}" method="POST" onclick="return confirm('{{__('admin_cards.delete_question')}}')">
                                  @csrf
                                  @method('DELETE')
                                  <button type="submit" class="btn btn-sm btn-danger mx-auto" style="width:90%">
                                    <i class="fas fa-trash-alt me-1"></i><span class="disappear-1300">{{__('admin_cards.table_delete')}}</span>
                                  </button>
                                </form>
                              </div>
                            </div>
                          </td>
                        </tr>
                        @endforeach
                      </tbody>
                      <tfoot>
                        <tr>
                            <th style="width: 5%;">{{__('admin_cards.table_order')}}</th>
                            <th style="width: 20%;">{{__('admin_cards.table_icon')}}</th>
                            <th style="width: 20%;">{{__('admin_cards.table_title')}}</th>
                            @desktop <th style="width: 10%;">{{__('admin_cards.table_description')}}</th> @enddesktop
                            <th style="width: 20%;">{{__('admin_cards.table_actions')}}</th>
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
