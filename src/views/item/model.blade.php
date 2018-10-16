@extends($layout ? 'master::layouts/admin' : 'master::layouts/child-admin')

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
  @if($pdf===false)
    {!! AdminItem::make_item_header($i, $module, $node, $action, $layout, $parent_id) !!}
  @else
    <h1>{{ $title }}</h1>
  @endif
  @if($dt!='view')
    {!! Form::open(AdminItem::make_form($module, $model, $action, $files)) !!}
  @else
    <div class="form-horizontal">
  @endif
  @if(($action=='edit'||$action=='view')&&isset($parent_nodes)&&count($parent_nodes)>0)
    @include('master::includes.parent-form')
    <h4>{{ $node->singular }}</h4>
  @endif
  @include('master::includes.form')
  @if(!$layout)
    <input type="hidden" name="child-page" value="1">
    <input type="hidden" name="child-url" value="{{ request()->fullUrlWithQuery([]) }}">
  @endif
  @if($dt!='view')
    {!! Field::form_submit($i, $model, $action) !!}
    {!! Form::close() !!}
    @include('master::helpers.activities')
  @else
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
  @if(config('solunes.store')&&config('store.item_add_script')&&array_key_exists($node->name, config('store.item_add_script')))
    @foreach(config('store.item_add_script')[$node->name] as $file)
      @include('store::scripts.'.$file.'-js')
    @endforeach
  @endif
@endsection