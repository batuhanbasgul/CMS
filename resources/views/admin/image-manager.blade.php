@extends('admin.layouts.base')

@section('title',__('admin_image_manager.image_settings'))

@section('content')
<!-- Main Container -->
<div class="page-body pt-5">
  <div class="container-fluid">
    <div class="page-header">
      <div class="row">
        <div class="col-7">
          <h3>{{__('admin_image_manager.image_settings')}} </h3>
          <ol class="breadcrumb">
            <li class="breadcrumb-item disappear-500"><a href="{{ route('admin.index') }}"><ins>{{__('admin_image_manager.main_page')}}</ins></a></li>
            <li class="breadcrumb-item disappear-500">{{__('admin_image_manager.images')}}</li>
            <li class="breadcrumb-item disappear-500">{{__('admin_image_manager.image_settings')}}</li>
          </ol>
        </div>
        <div class="col-5 d-flex justify-content-end">
        </div>
      </div>
    </div>
  </div>
<!-- Sweet Alert -->
@if(session('success'))
<script>swal("{{__('admin_image_manager.success')}}", "{{__('admin_image_manager.success_process')}}", "success");</script>
@elseif(session('error'))
<script>swal("{{__('admin_image_manager.error')}}", "{{__('admin_image_manager.error_process')}}", "error");</script>
@endif
  <!-- Container-fluid starts-->
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            @php
              $name = null;
              $counter = 1;
              $local_counter = 1;
            @endphp
            @foreach ($image_settings as $item)
              @if($name == null)
                <div class="card-block row">
                  <div class="col-sm-12 col-lg-12 col-xl-12">
                    <div class="table-responsive">
                      <table class="table table-responsive-sm">
                        <thead>
                          <tr>
                            <th scope="col" style="width: 5%;">#</th>
                            <th scope="col" style="width: 20%;">{{ ucwords(explode('_',$item->type)[0]) }}</th>
                            <th scope="col" style="width: 30%;">{{__('admin_image_manager.scales')}}</th>
                            <th scope="col" style="width: 15%;">{{__('admin_image_manager.size')}}</th>
                            <th scope="col" style="width: 15%;">{{__('admin_image_manager.quality')}}</th>
                            <th scope="col" style="width: 15%;"></th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <th scope="row">{{$local_counter}}</th>
                            <td>
                              @foreach (explode('_',$item->type) as $word)
                                @if ($word == explode('_',$item->type)[0])
                                  @continue
                                @endif
                                {{ ucwords($word).' ' }}
                              @endforeach
                            </td>
                            <form action="{{ route('admin.image-manager.update', $item->id) }}" method="POST">
                              @csrf
                              @method('PUT')
                              <td>
                                <div class="row">
                                  <div class="col-6">
                                    <input type="number" class="form-control" id="width" name="width" placeholder="{{__('admin_image_manager.width')}}" value="{{ $item->width }}">
                                  </div>
                                  <div class="col-6">
                                    <input type="number" class="form-control" id="height" name="height" placeholder="{{__('admin_image_manager.height')}}" value="{{ $item->height }}">
                                  </div>
                                </div>
                              </td>
                              <td>
                                <input type="number" class="form-control" id="file_size" name="file_size" placeholder="{{__('admin_image_manager.file_size')}}" min="1" max="1024000" value="{{ $item->file_size/1024 }}">
                              </td>
                              <td>
                                <input type="number" class="form-control" id="quality" name="quality" placeholder="{{__('admin_image_manager.file_quality')}}" value="{{ $item->quality }}">
                              </td>
                              <td>
                                <div class="input-group" >
                                  <input style="width:100%;" type="submit" class="btn btn-primary" id="updateimagesettings" data-toggle="click-ripple" name="updateimagesettings" value="{{__('admin_image_manager.update')}}">
                                </div>
                              </td>
                            </form>
                          </tr>
                            @php $name = explode('_',$item->type)[0]  @endphp
                            @elseif($name == explode('_',$item->type)[0])
                                <tr>
                                <th scope="row">{{$local_counter}}</th>
                                <td>
                                    @foreach (explode('_',$item->type) as $word)
                                    @if ($word == explode('_',$item->type)[0])
                                        @continue
                                    @endif
                                    {{ ucwords($word).' ' }}
                                    @endforeach
                                </td>
                                <form action="{{ route('admin.image-manager.update', $item->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <td>
                                    <div class="row">
                                        <div class="col-6">
                                        <input type="number" class="form-control" id="width" name="width" placeholder="{{__('admin_image_manager.width')}}" value="{{ $item->width }}">
                                        </div>
                                        <div class="col-6">
                                        <input type="number" class="form-control" id="height" name="height" placeholder="{{__('admin_image_manager.height')}}" value="{{ $item->height }}">
                                        </div>
                                    </div>
                                    </td>
                                    <td>
                                    <input type="number" class="form-control" id="file_size" name="file_size" placeholder="{{__('admin_image_manager.file_size')}}" min="1" max="1024000" value="{{ $item->file_size/1024 }}">
                                    </td>
                                    <td>
                                    <input type="number" class="form-control" id="quality" name="quality" placeholder="{{__('admin_image_manager.file_quality')}}" value="{{ $item->quality }}">
                                    </td>
                                    <td>
                                    <div class="input-group" >
                                        <input style="width:100%;" type="submit" class="btn btn-primary" id="updateimagesettings" data-toggle="click-ripple" name="updateimagesettings" value="{{__('admin_image_manager.update')}}">
                                    </div>
                                    </td>
                                </form>
                                </tr>
                                @if($image_settings[count($image_settings)-1]->id == $item->id)
                                        </tbody>
                                        </table>
                                    </div>
                                    </div>
                                </div>
                                @endif
                            @else
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
                @php
                  $local_counter = 1;
                @endphp
                <div class="card-block row">
                  <div class="col-sm-12 col-lg-12 col-xl-12">
                    <div class="table-responsive">
                      <table class="table table-responsive-sm">
                        <thead>
                          <tr>
                            <th scope="col" style="width: 5%;">#</th>
                            <th scope="col" style="width: 20%;">{{ ucwords(explode('_',$item->type)[0]) }}</th>
                            <th scope="col" style="width: 30%;">{{__('admin_image_manager.scales')}}</th>
                            <th scope="col" style="width: 15%;">{{__('admin_image_manager.size')}}</th>
                            <th scope="col" style="width: 15%;">{{__('admin_image_manager.quality')}}</th>
                            <th scope="col" style="width: 15%;"></th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <th scope="row">{{$local_counter}}</th>
                            <td>
                              @foreach (explode('_',$item->type) as $word)
                                @if ($word == explode('_',$item->type)[0])
                                  @continue
                                @endif
                                {{ ucwords($word).' ' }}
                              @endforeach
                            </td>
                            <form action="{{ route('admin.image-manager.update', $item->id) }}" method="POST">
                              @csrf
                              @method('PUT')
                              <td>
                                <div class="row">
                                  <div class="col-6">
                                    <input type="number" class="form-control" id="width" name="width" placeholder="{{__('admin_image_manager.width')}}" value="{{ $item->width }}">
                                  </div>
                                  <div class="col-6">
                                    <input type="number" class="form-control" id="height" name="height" placeholder="{{__('admin_image_manager.height')}}" value="{{ $item->height }}">
                                  </div>
                                </div>
                              </td>
                              <td>
                                <input type="number" class="form-control" id="file_size" name="file_size" placeholder="{{__('admin_image_manager.file_size')}}" min="1" max="1024000" value="{{ $item->file_size/1024 }}">
                              </td>
                              <td>
                                <input type="number" class="form-control" id="quality" name="quality" placeholder="{{__('admin_image_manager.file_quality')}}" value="{{ $item->quality }}">
                              </td>
                              <td>
                                <div class="input-group" >
                                  <input style="width:100%;" type="submit" class="btn btn-primary" id="updateimagesettings" data-toggle="click-ripple" name="updateimagesettings" value="{{__('admin_image_manager.update')}}">
                                </div>
                              </td>
                            </form>
                          </tr>
                @php $name = explode('_',$item->type)[0]  @endphp
              @endif
              @php
                $counter++;
                $local_counter++;
              @endphp
            @endforeach
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Container-fluid Ends-->
</div>

@endsection
