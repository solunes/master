@extends('master::layouts/admin')

@section('css')
  <link rel="stylesheet" href="{{ asset('assets/admin/css/froala.css') }}">
  @include('master::scripts.lightbox-css')
@endsection
@section('content')
  <h3>
    <a href="{{ url('admin/form-list') }}">Admin de Formularios</a>
     | <a href="{{ url('admin/form-fields/'.$id) }}">Editar Campos</a> | {{ trans('master::admin.'.$action) }} Formulario
   </h3>
  {!! Form::open(['name'=>$action.'_field', 'id'=>$action.'_table', 'role'=>'form', 'url'=>'admin/form', 'class'=>'form-horizontal prevent-double-submit', 'autocomplete'=>'off']) !!}
  <div class="row flex">
    @if($action=='create')
      {!! Field::form_input($i, $dt, ['name'=>'name', 'required'=>true, 'type'=>'string'], ['label'=>'Código de Formulario (Ej: riasec)', 'cols'=>6]) !!}
      {!! Field::form_input($i, $dt, ['name'=>'permission', 'required'=>true, 'type'=>'select', 'options'=>['estudiante'=>'Estudiante', 'docente'=>'Docente', 'empresa'=>'Empresa', 'orientacion'=>'Orientación Vocacional', 'form'=>'Formulario de Retroalimentación']], ['label'=>'Tipo de Formulario', 'cols'=>6]) !!}

      {!! Field::form_input($i, $dt, ['name'=>'menu_parent', 'required'=>true, 'type'=>'select', 'options'=>$options_menu], ['label'=>'Ubicación dentro del Menú', 'cols'=>6]) !!}
    @endif
    {!! Field::form_input(NULL, $dt, ['name'=>'menu_name', 'required'=>true, 'type'=>'string'], ['label'=>'Título del Menú', 'cols'=>6, 'value'=>$menu_name]) !!}
  </div>
  <div class="row flex">
    {!! Field::form_input($i, $dt, ['name'=>'singular', 'required'=>true, 'type'=>'string'], ['label'=>'Nombre de la Tabla (Singular)', 'cols'=>6]) !!}
    {!! Field::form_input($i, $dt, ['name'=>'plural', 'required'=>true, 'type'=>'string'], ['label'=>'Nombre de la Tabla (Plural)', 'cols'=>6]) !!}
    {!! Field::form_input($i, $dt, ['name'=>'additional_permission', 'required'=>true, 'type'=>'select', 'options'=>['none'=>'Visto por todos', 'admin'=>'Visto solo por administradores']], ['label'=>'Permiso del Formulario', 'cols'=>6]) !!}
  </div>
  {!! Field::form_submit($i, $model, $action) !!}
  {!! Form::close() !!}
@endsection
@section('script')
  @include('master::helpers.froala')
  @include('master::scripts.child-js')
  @include('master::scripts.upload-js')
  @include('master::scripts.map-js')
  @include('master::scripts.map-field-js')
  @include('master::scripts.lightbox-js')
  @include('master::scripts.tooltip-js')
  @include('master::scripts.accordion-js')
  @include('master::scripts.leave-form-js')
@endsection