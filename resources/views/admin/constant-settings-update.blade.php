@extends('admin.layouts.base')

@section('title',__('admin_constants.update_constants'))

@section('content')
<!-- Main Container -->
<div class="page-body pt-5">
  <div class="container-fluid">
    <div class="page-header">
      <div class="row">
        <div class="col-7">
            <div class="row">
                <div class="col-7">
                    <h3>{{__('admin_constants.update_constants')}} @if(count($langs) != 1)<span class="title-lang-code">({{ request('lang_code') }})</span>@endif</h3>
                    <ol class="breadcrumb">
                      <li class="breadcrumb-item disappear-500"><a href="{{ route('admin.index') }}"><ins> {{__('admin_constants.main_page')}}</ins></a></li>
                      <li class="breadcrumb-item disappear-500">{{__('admin_constants.constants')}}</li>
                      <li class="breadcrumb-item disappear-500">{{__('admin_constants.update_constants')}}</li>
                    </ol>
                </div>
                <div class="col-5">
                    <!--
                      Refresh the page with selected lang_code.
                    -->
                    @if(count($langs) != 1)
                      @foreach ($langs as $lang)
                        @if ($lang_code==$lang->lang_code)
                        <div class="mx-2 d-none">
                          <span class="disabled">({{__('admin_constants.current_language')}})</span>
                          <img src="{{ asset($lang->icon) }}" alt="">
                        </div>
                        @endif
                      @endforeach
                      <select class="form-select" id="lang-select" name="lang-select" onchange="window.location.href=this.options[this.selectedIndex].value;">
                          @foreach ($langs as $lang)
                          @if ($lang->is_active)
                          <option value="{{ route('admin.constant-settings.index',['lang_code'=>$lang->lang_code, 'page'=>request('page')]) }}"
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
        </div>
      </div>
    </div>
  </div>
  <!-- Sweet Alert -->
  @if ($errors->any())
    @error('lang_code')
      <div>
        <script>swal("{{__('admin_constants.error')}}", "{{__('admin_constants.update_error_context')}}", "error");</script>
      </div>
    @enderror
  @else
    @if(session('success'))
    <script>swal("{{__('admin_constants.success')}}", "{{__('admin_constants.update_success_context')}}", "success");</script>
    @elseif(session('error'))
    <script>swal("{{__('admin_constants.error')}}", "{{__('admin_constants.update_error_context')}}", "error");</script>
    @endif
  @endif
  <!-- Container-fluid starts-->
  <div class="container-fluid">
    <div class="card">
      <div class="card-body">
        <ul class="nav nav-tabs border-tab" id="top-tab" role="tablist">
          <li class="nav-item"><a class="nav-link @if (!session()->get('tab_page') || session()->get('tab_page') == 'page_constant') active @endif" data-bs-toggle="tab"
              href="#top-page-constant" role="tab" aria-controls="top-page-constant" aria-selected="true">
              <i class="fa-solid fa-hammer"></i>{{__('admin_constants.page_constants')}}</a></li>
          <li class="nav-item"><a class="nav-link @if (session()->get('tab_page') == 'contact_constant') active @endif" data-bs-toggle="tab"
              href="#top-contact-constant" role="tab" aria-controls="top-contact-constant" aria-selected="false">
              <i class="fa-solid fa-phone"></i>{{__('admin_constants.contact_constants')}}</a></li>
          <li class="nav-item"><a class="nav-link @if (session()->get('tab_page') == 'contact_form_constant') active @endif" data-bs-toggle="tab"
              href="#top-contact-form-constant" role="tab" aria-controls="top-contact-form-constant" aria-selected="false">
              <i class="fa-solid fa-file-lines"></i>{{__('admin_constants.contact_form_constants')}}</a></li>
          <li class="nav-item"><a class="nav-link @if (session()->get('tab_page') == 'products_constant') active @endif" data-bs-toggle="tab"
              href="#top-products-constant" role="tab" aria-controls="top-products-constant" aria-selected="false">
              <i class="fa-solid fa-leaf"></i>{{__('admin_constants.products_page_constants')}}</a></li>
          <li class="nav-item"><a class="nav-link @if (session()->get('tab_page') == 'footer_constant') active @endif" data-bs-toggle="tab"
              href="#top-footer-constant" role="tab" aria-controls="top-footer-constant" aria-selected="false">
              <i class="fa-solid fa-tent-arrow-down-to-line"></i>{{__('admin_constants.footer_constants')}}</a></li>
          <li class="nav-item"><a class="nav-link @if (session()->get('tab_page') == 'accept_cookies_constant') active @endif" data-bs-toggle="tab"
              href="#top-accept-cookies-constant" role="tab" aria-controls="top-accept-cookies-constant" aria-selected="false">
              <i class="fa-solid fa-scroll"></i>{{__('admin_constants.cookies_constants')}}</a></li>
        </ul>
        <div class="tab-content" id="top-tabContent">
          <div class="tab-pane fade @if (!session()->get('tab_page') || session()->get('tab_page') == 'page_constant') show active @endif" id="top-page-constant" role="tabpanel" aria-labelledby="top-page-constant">
            <form class="theme-form" action="{{ route('admin.constant-settings.update',[$content->id]) }}" method="POST">
              @csrf
              @method('PUT')
              <input type="hidden" name="page" value="{{ request('page') }}">
              <input type="hidden" name="lang_code" value="{{ request('lang_code') }}">
              <h4 class="border-bottom pb-2">{{__('admin_constants.page_constants')}}</h4>
              <div class="mb-4">
                <label class="form-label p-0" for="title">{{__('admin_constants.title')}}</label>
                <input type="text" class="form-control" id="title" name="title" placeholder="{{__('admin_constants.title_placeholder')}}" value="{{ $content->title }}">
              </div>
              <div class="mb-4">
                <label class="form-label p-0" for="subtitle">{{__('admin_constants.subtitle')}}</label>
                <input type="text" class="form-control" id="subtitle" name="subtitle" placeholder="{{__('admin_constants.title_placeholder')}}" value="{{ $content->subtitle }}">
              </div>
              <div class="mb-4">
                <label class="form-label p-0" for="date">{{__('admin_constants.date')}}</label>
                <input type="text" class="form-control" id="date" name="date" placeholder="{{__('admin_constants.date_placeholder')}}" value="{{ $content->date }}">
              </div>
              <div class="mb-4">
                <label class="form-label p-0" for="author">{{__('admin_constants.author')}}</label>
                <input type="text" class="form-control" id="author" name="author" placeholder="{{__('admin_constants.author_placeholder')}}" value="{{ $content->author }}">
              </div>
              <div class="mb-4">
                <label class="form-label p-0" for="read_more">{{__('admin_constants.read_more')}}</label>
                <input type="text" class="form-control" id="read_more" name="read_more" placeholder="{{__('admin_constants.read_more_placeholder')}}" value="{{ $content->read_more }}">
              </div>
              <div class="mb-4">
                <label class="form-label p-0" for="detail">{{__('admin_constants.detail')}}</label>
                <input type="text" class="form-control" id="detail" name="detail" placeholder="{{__('admin_constants.detail_placeholder')}}" value="{{ $content->detail }}">
              </div>
              <div class="mb-4">
                <label class="form-label p-0" for="watch_video">{{__('admin_constants.watch_video')}}</label>
                <input type="text" class="form-control" id="watch_video" name="watch_video" placeholder="{{__('admin_constants.watch_video_placeholder')}}" value="{{ $content->watch_video }}">
              </div>
              <div class="mb-4">
                <label class="form-label p-0" for="buy_title">{{__('admin_constants.buy_title')}}</label>
                <input type="text" class="form-control" id="buy_title" name="buy_title" placeholder="{{__('admin_constants.buy_title_placeholder')}}" value="{{ $content->buy_title }}">
              </div>
              <div class="mb-4">
                <label class="form-label p-0" for="buy_subtitle">{{__('admin_constants.buy_subtitle')}}</label>
                <input type="text" class="form-control" id="buy_subtitle" name="buy_subtitle" placeholder="{{__('admin_constants.buy_subtitle_placeholder')}}" value="{{ $content->buy_subtitle }}">
              </div>
              <div class="mb-4">
                <label class="form-label p-0" for="buy_price_button">{{__('admin_constants.buy_price_button')}}</label>
                <input type="text" class="form-control" id="buy_price_button" name="buy_price_button" placeholder="{{__('admin_constants.buy_price_button')}}" value="{{ $content->buy_price_button }}">
              </div>
              <div class="mb-4">
                <label class="form-label p-0" for="buy_contact_button">{{__('admin_constants.buy_contact_button')}}</label>
                <input type="text" class="form-control" id="buy_contact_button" name="buy_contact_button" placeholder="{{__('admin_constants.buy_contact_button_placeholder')}}" value="{{ $content->buy_contact_button }}">
              </div>
              <div class="mb-4">
                <label class="form-label p-0" for="close_lang">{{__('admin_constants.close_lang')}}</label>
                <input type="text" class="form-control" id="close_lang" name="close_lang" placeholder="{{__('admin_constants.close_lang_placeholder')}}" value="{{ $content->close_lang }}">
              </div>
              <div class="mb-4">
                <label class="form-label p-0" for="keywords">{{__('admin_constants.keywords')}}</label>
                <textarea class="form-control" id="keywords" name="keywords" rows="4" placeholder="{{__('admin_constants.keywords_placeholder')}}">{{ $content->keywords }}</textarea>
              </div>

              <div class="mb-4">
                <div class="row">
                  <div class="col-md-6 col-lg-8 col-xl-8 col-xxl-9"></div>
                  <div class="col-md-6 col-lg-4 col-xl-4 col-xxl-3">
                    <div class="input-group" >
                      <input style="width:100%;" type="submit" class="btn btn btn-primary my-0 mx-1" id="updatepageconstant" name="updatepageconstant" value="{{__('admin_constants.update')}}">
                    </div>
                  </div>
                </div>
              </div>
            </form>
          </div>
          <div class="tab-pane fade @if (session()->get('tab_page') == 'contact_constant') show active @endif" id="top-contact-constant" role="tabpanel" aria-labelledby="top-contact-constant">
            <form class="theme-form" action="{{ route('admin.constant-settings.update',[$content->id]) }}" method="POST">
              @csrf
              @method('PUT')
              <input type="hidden" name="page" value="{{ request('page') }}">
              <input type="hidden" name="lang_code" value="{{ request('lang_code') }}">
              <h4 class="border-bottom pb-2">{{__('admin_constants.contact_constants')}}</h4>
              <div class="mb-4">
                <label class="form-label p-0" for="phone_1">{{__('admin_constants.first_phone')}}</label>
                <input type="text" class="form-control" id="phone_1" name="phone_1" placeholder="{{__('admin_constants.first_phone_placeholder')}}" value="{{ $content->phone_1 }}">
              </div>
              <div class="mb-4">
                <label class="form-label p-0" for="phone_2">{{__('admin_constants.second_phone')}}</label>
                <input type="text" class="form-control" id="phone_2" name="phone_2" placeholder="{{__('admin_constants.second_phone_placeholder')}}" value="{{ $content->phone_2 }}">
              </div>
              <div class="mb-4">
                <label class="form-label p-0" for="gsm_1">{{__('admin_constants.first_gsm')}}</label>
                <input type="text" class="form-control" id="gsm_1" name="gsm_1" placeholder="{{__('admin_constants.first_gsm_placeholder')}}" value="{{ $content->gsm_1 }}">
              </div>
              <div class="mb-4">
                <label class="form-label p-0" for="gsm_2">{{__('admin_constants.second_gsm')}}</label>
                <input type="text" class="form-control" id="gsm_2" name="gsm_2" placeholder="{{__('admin_constants.second_gsm_placeholder')}}" value="{{ $content->gsm_2 }}">
              </div>
              <div class="mb-4">
                <label class="form-label p-0" for="email_1">{{__('admin_constants.first_email')}}</label>
                <input type="text" class="form-control" id="email_1" name="email_1" placeholder="{{__('admin_constants.first_email_placeholder')}}" value="{{ $content->email_1 }}">
              </div>
              <div class="mb-4">
                <label class="form-label p-0" for="email_2">{{__('admin_constants.second_email')}}</label>
                <input type="text" class="form-control" id="email_2" name="email_2" placeholder="{{__('admin_constants.second_email_placeholder')}}" value="{{ $content->email_2 }}">
              </div>
              <div class="mb-4">
                <label class="form-label p-0" for="address_1">{{__('admin_constants.first_address')}}</label>
                <input type="text" class="form-control" id="address_1" name="address_1" placeholder="{{__('admin_constants.first_address_placeholder')}}" value="{{ $content->address_1 }}">
              </div>
              <div class="mb-4">
                <label class="form-label p-0" for="address_2">{{__('admin_constants.second_address')}}</label>
                <input type="text" class="form-control" id="address_2" name="address_2" placeholder="{{__('admin_constants.second_address_placeholder')}}" value="{{ $content->address_2 }}">
              </div>
              <div class="mb-4">
                <div class="row">
                  <div class="col-md-6 col-lg-8 col-xl-8 col-xxl-9"></div>
                  <div class="col-md-6 col-lg-4 col-xl-4 col-xxl-3">
                    <div class="input-group" >
                      <input style="width:100%;" type="submit" class="btn btn btn-primary my-0 mx-1" id="updatecontactconstant" name="updatecontactconstant" value="{{__('admin_constants.update')}}">
                    </div>
                  </div>
                </div>
              </div>
            </form>
          </div>
          <div class="tab-pane fade @if (session()->get('tab_page') == 'contact_form_constant') show active @endif" id="top-contact-form-constant" role="tabpanel" aria-labelledby="top-contact-form-constant">
            <form class="theme-form" action="{{ route('admin.constant-settings.update',[$content->id]) }}" method="POST">
              @csrf
              @method('PUT')
              <input type="hidden" name="page" value="{{ request('page') }}">
              <input type="hidden" name="lang_code" value="{{ request('lang_code') }}">
              <h4 class="border-bottom pb-2">{{__('admin_constants.contact_form_constants')}}</h4>
              <div class="mb-4">
                <label class="form-label p-0" for="contact_name">{{__('admin_constants.name_surname_title')}} </label>
                <input type="text" class="form-control" id="contact_name" name="contact_name" placeholder="{{__('admin_constants.name_surname_title_placeholder')}}" value="{{ $content->contact_name }}">
              </div>
              <div class="mb-4">
                <label class="form-label p-0" for="contact_mail">{{__('admin_constants.email_title')}}</label>
                <input type="text" class="form-control" id="contact_mail" name="contact_mail" placeholder="{{__('admin_constants.email_title_placeholder')}}" value="{{ $content->contact_mail }}">
              </div>
              <div class="mb-4">
                <label class="form-label p-0" for="contact_phone">{{__('admin_constants.phone_title')}}</label>
                <input type="text" class="form-control" id="contact_phone" name="contact_phone" placeholder="{{__('admin_constants.phone_title_placeholder')}}" value="{{ $content->contact_phone }}">
              </div>
              <div class="mb-4">
                <label class="form-label p-0" for="subject">{{__('admin_constants.subject_title')}}</label>
                <input type="text" class="form-control" id="subject" name="subject" placeholder="{{__('admin_constants.subject_title_placeholder')}}" value="{{ $content->subject }}">
              </div>
              <div class="mb-4">
                <label class="form-label p-0" for="message">{{__('admin_constants.message_title')}}</label>
                <input type="text" class="form-control" id="message" name="message" placeholder="{{__('admin_constants.message_title_placeholder')}}" value="{{ $content->message }}">
              </div>
              <div class="mb-4">
                <label class="form-label p-0" for="send_button">{{__('admin_constants.send_button')}}</label>
                <input type="text" class="form-control" id="send_button" name="send_button" placeholder="{{__('admin_constants.send_button_placeholder')}}" value="{{ $content->send_button }}">
              </div>
              <div class="mb-4">
                <label class="form-label p-0" for="sent_message_success">{{__('admin_constants.sent_message_success')}}</label>
                <input type="text" class="form-control" id="sent_message_success" name="sent_message_success" placeholder="{{__('admin_constants.sent_message_success_placeholder')}}" value="{{ $content->sent_message_success }}">
              </div>
              <div class="mb-4">
                <label class="form-label p-0" for="sent_name_error">{{__('admin_constants.sent_name_error')}}</label>
                <input type="text" class="form-control" id="sent_name_error" name="sent_name_error" placeholder="{{__('admin_constants.sent_name_error_placeholder')}}" value="{{ $content->sent_name_error }}">
              </div>
              <div class="mb-4">
                <label class="form-label p-0" for="sent_mail_error">{{__('admin_constants.sent_mail_error')}}</label>
                <input type="text" class="form-control" id="sent_mail_error" name="sent_mail_error" placeholder="{{__('admin_constants.sent_mail_error_placeholder')}}" value="{{ $content->sent_mail_error }}">
              </div>
              <div class="mb-4">
                <label class="form-label p-0" for="sent_subject_error">{{__('admin_constants.sent_subject_error')}}</label>
                <input type="text" class="form-control" id="sent_subject_error" name="sent_subject_error" placeholder="{{__('admin_constants.sent_subject_error_placeholder')}}" value="{{ $content->sent_subject_error }}">
              </div>
              <div class="mb-4">
                <label class="form-label p-0" for="sent_message_error">{{__('admin_constants.sent_message_error')}}</label>
                <input type="text" class="form-control" id="sent_message_error" name="sent_message_error" placeholder="{{__('admin_constants.sent_message_error_placeholder')}}" value="{{ $content->sent_message_error }}">
              </div>
              <div class="mb-4">
                <label class="form-label p-0" for="sent_validation_error">{{__('admin_constants.sent_validation_error')}}</label>
                <input type="text" class="form-control" id="sent_validation_error" name="sent_validation_error" placeholder="{{__('admin_constants.sent_validation_error_placeholder')}}" value="{{ $content->sent_validation_error }}">
              </div>
              <div class="mb-4">
                <div class="row">
                  <div class="col-md-6 col-lg-8 col-xl-8 col-xxl-9"></div>
                  <div class="col-md-6 col-lg-4 col-xl-4 col-xxl-3">
                    <div class="input-group" >
                      <input style="width:100%;" type="submit" class="btn btn btn-primary my-0 mx-1" id="updatecontactformconstant" name="updatecontactformconstant" value="{{__('admin_constants.update')}}">
                    </div>
                  </div>
                </div>
              </div>
            </form>
          </div>
          <div class="tab-pane fade @if (session()->get('tab_page') == 'products_constant') show active @endif" id="top-products-constant" role="tabpanel" aria-labelledby="top-products-constant">
            <form class="theme-form" action="{{ route('admin.constant-settings.update',[$content->id]) }}" method="POST">
              @csrf
              @method('PUT')
              <input type="hidden" name="page" value="{{ request('page') }}">
              <input type="hidden" name="lang_code" value="{{ request('lang_code') }}">
              <h4 class="border-bottom pb-2">{{__('admin_constants.products_page_constants')}}</h4>
              <div class="mb-4">
                <label class="form-label p-0" for="categories">{{__('admin_constants.categories_title')}}</label>
                <input type="text" class="form-control" id="categories" name="categories" placeholder="{{__('admin_constants.categories_title_placeholder')}}" value="{{ $content->categories }}">
              </div>
              <div class="mb-4">
                <label class="form-label p-0" for="all_products">{{__('admin_constants.products_title')}}</label>
                <input type="text" class="form-control" id="all_products" name="all_products" placeholder="{{__('admin_constants.products_title_placeholder')}}" value="{{ $content->all_products }}">
              </div>
              <div class="mb-4">
                <label class="form-label p-0" for="product_no">{{__('admin_constants.product_no_title')}}</label>
                <input type="text" class="form-control" id="product_no" name="product_no" placeholder="{{__('admin_constants.product_no_title_placeholder')}}" value="{{ $content->product_no }}">
              </div>
              <div class="mb-4">
                <label class="form-label p-0" for="product_info">{{__('admin_constants.product_info_title')}}</label>
                <input type="text" class="form-control" id="product_info" name="product_info" placeholder="{{__('admin_constants.product_info_title_placeholder')}}" value="{{ $content->product_info }}">
              </div>
              <div class="mb-4">
                <label class="form-label p-0" for="product_name">{{__('admin_constants.product_name_title')}}</label>
                <input type="text" class="form-control" id="product_name" name="product_name" placeholder="{{__('admin_constants.product_name_title_placeholder')}}" value="{{ $content->product_name }}">
              </div>
              <div class="mb-4">
                <label class="form-label p-0" for="product_price">{{__('admin_constants.product_price_title')}}</label>
                <input type="text" class="form-control" id="product_price" name="product_price" placeholder="{{__('admin_constants.product_price_title_placeholder')}}" value="{{ $content->product_price }}">
              </div>
              <div class="mb-4">
                <label class="form-label p-0" for="no_product">{{__('admin_constants.no_product_title')}}</label>
                <input type="text" class="form-control" id="no_product" name="no_product" placeholder="{{__('admin_constants.no_product_title_placeholder')}}" value="{{ $content->no_product }}">
              </div>
              <div class="mb-4">
                <label class="form-label p-0" for="product_keywords">{{__('admin_constants.product_keywords')}}</label>
                <input type="text" class="form-control" id="product_keywords" name="product_keywords" placeholder="{{__('admin_constants.product_keywords_placeholder')}}" value="{{ $content->product_keywords }}">
              </div>
              <div class="mb-4">
                <div class="row">
                  <div class="col-md-6 col-lg-8 col-xl-8 col-xxl-9"></div>
                  <div class="col-md-6 col-lg-4 col-xl-4 col-xxl-3">
                    <div class="input-group" >
                      <input style="width:100%;" type="submit" class="btn btn btn-primary my-0 mx-1" id="updateproductsconstant" name="updateproductsconstant" value="{{__('admin_constants.update')}}">
                    </div>
                  </div>
                </div>
              </div>
            </form>
          </div>
          <div class="tab-pane fade @if (session()->get('tab_page') == 'footer_constant') show active @endif" id="top-footer-constant" role="tabpanel" aria-labelledby="top-footer-constant">
            <form class="theme-form" action="{{ route('admin.constant-settings.update',[$content->id]) }}" method="POST">
              @csrf
              @method('PUT')
              <input type="hidden" name="page" value="{{ request('page') }}">
              <input type="hidden" name="lang_code" value="{{ request('lang_code') }}">
              <h4 class="border-bottom pb-2">{{__('admin_constants.footer_constants')}}</h4>
              <div class="mb-4">
                <label class="form-label p-0" for="phone">{{__('admin_constants.footer_phone_title')}}</label>
                <input type="text" class="form-control" id="phone" name="phone" placeholder="{{__('admin_constants.footer_phone_title_placeholder')}}" value="{{ $content->phone }}">
              </div>
              <div class="mb-4">
                <label class="form-label p-0" for="email">{{__('admin_constants.footer_email_title')}}</label>
                <input type="text" class="form-control" id="email" name="email" placeholder="{{__('admin_constants.footer_email_title_placeholder')}}" value="{{ $content->email }}">
              </div>
              <div class="mb-4">
                <label class="form-label p-0" for="address">{{__('admin_constants.footer_address_title')}}</label>
                <input type="text" class="form-control" id="address" name="address" placeholder="{{__('admin_constants.footer_address_title_placeholder')}}" value="{{ $content->address }}">
              </div>
              <div class="mb-4">
                <label class="form-label p-0" for="quickmenu_title_1">{{__('admin_constants.footer_first_quickmenu_title')}}</label>
                <input type="text" class="form-control" id="quickmenu_title_1" name="quickmenu_title_1" placeholder="{{__('admin_constants.footer_first_quickmenu_title_placeholder')}}" value="{{ $content->quickmenu_title_1 }}">
              </div>
              <div class="mb-4">
                <label class="form-label p-0" for="quickmenu_title_2">{{__('admin_constants.footer_second_quickmenu_title')}}</label>
                <input type="text" class="form-control" id="quickmenu_title_2" name="quickmenu_title_2" placeholder="{{__('admin_constants.footer_second_quickmenu_title_placeholder')}}" value="{{ $content->quickmenu_title_2 }}">
              </div>
              <div class="mb-4">
                <label class="form-label p-0" for="copyright_description">{{__('admin_constants.footer_copyright_context')}}</label>
                <input type="text" class="form-control" id="copyright_description" name="copyright_description" placeholder="{{__('admin_constants.footer_copyright_context_placeholder')}}" value="{{ $content->copyright_description }}">
              </div>
              <div class="mb-4">
                <div class="row">
                  <div class="col-md-6 col-lg-8 col-xl-8 col-xxl-9"></div>
                  <div class="col-md-6 col-lg-4 col-xl-4 col-xxl-3">
                    <div class="input-group" >
                      <input style="width:100%;" type="submit" class="btn btn btn-primary my-0 mx-1" id="updatefooterconstant" name="updatefooterconstant" value="{{__('admin_constants.update')}}">
                    </div>
                  </div>
                </div>
              </div>
            </form>
          </div>
          <div class="tab-pane fade @if (session()->get('tab_page') == 'accept_cookies_constant') show active @endif" id="top-accept-cookies-constant" role="tabpanel" aria-labelledby="top-accept-cookies-constant">
            <form class="theme-form" action="{{ route('admin.constant-settings.update',[$content->id]) }}" method="POST">
              @csrf
              @method('PUT')
              <input type="hidden" name="page" value="{{ request('page') }}">
              <input type="hidden" name="lang_code" value="{{ request('lang_code') }}">
              <h4 class="border-bottom pb-2">{{__('admin_constants.cookies_constants')}}</h4>
              <div class="mb-4">
                <label class="form-label p-0" for="cookie_title">{{__('admin_constants.cookies_title')}}</label>
                <input type="text" class="form-control" id="cookie_title" name="cookie_title" placeholder="{{__('admin_constants.cookies_title_placeholder')}}" value="{{ $content->cookie_title }}">
              </div>
              <div class="mb-3">
                <label class="col-form-label p-0" for="cookie_description">{{__('admin_constants.cookies_description')}}</label>
                <textarea class="form-control" id="cookie_description" name="cookie_description" rows="6" placeholder="{{__('admin_constants.cookies_description_placeholder')}}">{{ $content->cookie_description }}</textarea>
              </div>
              <div class="mb-4">
                <label class="form-label p-0" for="cookie_button">{{__('admin_constants.cookies_button')}}</label>
                <input type="text" class="form-control" id="cookie_button" name="cookie_button" placeholder="{{__('admin_constants.cookies_button_placeholder')}}" value="{{ $content->cookie_button }}">
              </div>
              <div class="mb-4">
                <label class="form-label p-0" for="cookie_button_refuse">{{__('admin_constants.cookies_button_refuse')}}</label>
                <input type="text" class="form-control" id="cookie_button_refuse" name="cookie_button_refuse" placeholder="{{__('admin_constants.cookies_button_refuse_placeholder')}}" value="{{ $content->cookie_button_refuse }}">
              </div>
              <div class="mb-4">
                <label class="form-label p-0" for="link_title">{{__('admin_constants.link_title')}}</label>
                <input type="text" class="form-control" id="link_title" name="link_title" placeholder="{{__('admin_constants.link_title_placeholder')}}" value="{{ $content->link_title }}">
              </div>
              <div class="mb-4">
                <div class="row">
                  <div class="col-md-6 col-lg-8 col-xl-8 col-xxl-9"></div>
                  <div class="col-md-6 col-lg-4 col-xl-4 col-xxl-3">
                    <div class="input-group" >
                      <input style="width:100%;" type="submit" class="btn btn btn-primary my-0 mx-1" id="updateacceptcookies" name="updateacceptcookies" value="{{__('admin_constants.update')}}">
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
<script>
  ClassicEditor
      .create( document.querySelector( '#keywords' ) )
      .catch( error => {
          console.error( error );
      } );
</script>
@endsection
