@extends('master::layouts/admin-2')

@section('css')
  <link rel="stylesheet" href="{{ asset('assets/admin/css/froala.css') }}">
  @include('master::scripts.lightbox-css')
  @if(config('solunes.item_add_css')&&array_key_exists($node->name, config('solunes.item_add_css')))
    @foreach(config('solunes.item_add_css')[$node->name] as $file)
      @include('scripts.'.$file.'-css')
    @endforeach
  @endif
@endsection
@section('content')
  <div class="content-header row">
      <div class="content-header-left col-md-9 col-12 mb-2">
          <div class="row breadcrumbs-top">
              <div class="col-12">
                  <h2 class="content-header-title float-left mb-0">{{ $node->singular }}</h2>
                  <div class="breadcrumb-wrapper col-12">
                      <ol class="breadcrumb">
                          <li class="breadcrumb-item"><a href="index.html">Inicio</a>
                          </li>
                          <li class="breadcrumb-item active">{{ $node->singular }}
                          </li>
                      </ol>
                  </div>
              </div>
          </div>
      </div>
  </div>

<!-- Basic Horizontal form layout section start -->
<section id="basic-horizontal-layouts">
  <div class="match-height">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h4>{!! trans('master::admin.'.$action).' '.$node->singular.' | <a href="'.url($module.'/model-list/'.$node->name).'"><i class="fa fa-arrow-circle-o-left"></i> '.trans('master::admin.back').'</a>' !!}</h4>
          </div>
          <div class="card-content">
            <div class="card-body">
              {!! Form::open(AdminItem::make_form($module, $model, $action, $files)) !!}
              @include('master::includes.form')
              <div class="row"><div class="col-sm-12 left">
                {!! Form::hidden('action_form', $action) !!}
                {!! Form::hidden('model_node', $action) !!}
                @if($action=='edit')
                {!! Form::hidden('id', $i->id) !!}
                @endif
                {!! Form::submit(trans('master::admin.save'), array('class'=>'btn btn-primary mr-1 mb-1 btn-site')) !!}
              </div></div>
              {!! Form::close() !!}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- // Basic Horizontal form layout section end -->

<style>
  .flex-item {
    text-align: left
  }
  .flex-item label {
    padding: 30px 0px 5px 0px;
    font-size: 14px;
    font-weight: 600
  }
  .flex-item .mt-radio {
    padding: 20px 0px
  }
</style>

@endsection
@section('script')
  @include('master::helpers.froala')
  <?php $scripts_array = ['conditionals','upload','tooltip','accordion','radio']; ?>
  @if($barcode_enabled)
    <?php $scripts_array[] = 'barcode'; ?>
  @endif
  @if(!$layout)
    <?php $scripts_array[] = 'child-ajax'; ?>
    <?php $scripts_array[] = 'date'; ?>
    <?php $scripts_array[] = 'time'; ?>
  @else
    <?php $scripts_array = array_merge($scripts_array, ['map','child','leave-form','select','lightbox']); ?>
  @endif
  <?php $scripts_array[] = 'map-field'; ?>
  @if(config('solunes.item_remove_scripts')&&array_key_exists($node->name, config('solunes.item_remove_scripts')))
    <?php $scripts_array = array_diff($scripts_array, config('solunes.item_remove_scripts')[$node->name]); ?>
  @endif
  @if(config('solunes.store')&&config('store.item_remove_scripts')&&array_key_exists($node->name, config('store.item_remove_scripts')))
    <?php $scripts_array = array_diff($scripts_array, config('store.item_remove_scripts')[$node->name]); ?>
  @endif
  @foreach($scripts_array as $script)
    @include('master::scripts.'.$script.'-js')
  @endforeach
  @if(config('solunes.item_add_script')&&array_key_exists($node->name, config('solunes.item_add_script')))
    @foreach(config('solunes.item_add_script')[$node->name] as $file)
      @include('scripts.'.$file.'-js')
    @endforeach
  @endif
  @if(config('solunes.inventory')&&config('inventory.item_add_script')&&array_key_exists($node->name, config('inventory.item_add_script')))
    @foreach(config('inventory.item_add_script')[$node->name] as $file)
      @include('inventory::scripts.'.$file.'-js')
    @endforeach
  @endif
@endsection