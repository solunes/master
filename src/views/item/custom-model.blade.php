@extends(config('solunes.dashadmin_layout'))

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
@if(config('solunes.dashadmin_container'))
<div class="container">
@endif
@if(config('solunes.dashadmin_title'))
  @if($pdf===false)
    {!! AdminItem::make_item_header($i, $module, $node, $action, false, null) !!}
  @else
    <h1>{{ $title }}</h1>
  @endif
@endif
@if($dt!='view')
  {!! Form::open(AdminItem::make_form($module, $model, $action, $files)) !!}
@else
  <div class="form-horizontal">
@endif
@include('master::includes.form')
<input type="hidden" name="custom_type" value="{{ $custom_type }}" />
@if($dt!='view')
  {!! Field::form_submit($i, $model, $action) !!}
  {!! Form::close() !!}
@else
  </div>
@endif
@if(config('solunes.dashadmin_container'))
</div>
@endif
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