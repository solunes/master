@extends('master::layouts/admin')

@section('css')
  <link rel="stylesheet" href="{{ asset('assets/admin/css/froala.css') }}">
  @include('master::scripts.lightbox-css')
@endsection
@section('content')
  <h3><a href="{{ url('admin/form-fields/'.$field->parent_id.'#field_'.$field->name) }}">Atrás</a> | {{ trans('master::admin.'.$action) }} Campo</h3>
  {!! Form::open(['name'=>$action.'_field', 'id'=>$action.'_field', 'role'=>'form', 'url'=>'admin/form-field', 'class'=>'form-horizontal prevent-double-submit', 'autocomplete'=>'off']) !!}
  <div class="row flex">
    {!! Field::form_input($i, $dt, ['name'=>'type', 'required'=>true, 'type'=>'select', 'options'=>$types_array], ['label'=>'Tipo de Campo', 'cols'=>6]+$type_class) !!}
    {!! Field::form_input($i, $dt, ['name'=>'display_item', 'required'=>true, 'type'=>'select', 'options'=>['show'=>'Campo activo y visible para todos', 'admin'=>'Campo visible solo para administradores', 'none'=>'Campo oculto e inactivo para todos']], ['label'=>'¿Es visible en el formulario?', 'cols'=>6]) !!}
    {!! Field::form_input($i, $dt, ['name'=>'display_list', 'required'=>true, 'type'=>'select', 'options'=>['excel'=>'Solo en la descarga de Excel', 'show'=>'Visible en la tabla principal y excel', 'none'=>'No se muestra ni en la tabla ni en el excel']], ['label'=>'¿Es visible en el listado?', 'cols'=>6]) !!}
    {!! Field::form_input($i, $dt, ['name'=>'label', 'required'=>true, 'type'=>'string'], ['label'=>'Nombre del Campo', 'cols'=>6]) !!}
    {!! Field::form_input($i, $dt, ['name'=>'message', 'type'=>'string', 'message'=>'Este es un ejemplo de como se ve un mensaje adicional.'], ['label'=>'Campo de mensaje adicional', 'cols'=>6]) !!}
  </div>
  <div id="field_options" class="child">
    <label>Opciones del Campo</label>
    <div class="table-responsive">
      <table class="table" id="options">
        <thead><tr class="title">
          <td>Nº</td>
          <td>Nombre (*)</td>
          <td>Activo? (*)</td>
        </tr></thead>
        <tbody>
          @if($action=='edit'&&count($i->field_options)>0)
            @foreach($i->field_options as $key => $si)
              <tr>
                <td class="table-counter" data-count="{{ $key+1 }}">{{ $key+1 }}</td>
                <td>{!! Field::form_input($si, $dt, ['name'=>'label', 'required'=>true, 'type'=>'string'], ['subtype'=>'multiple', 'subinput'=>'options', 'subcount'=>$key]) !!}</td>
                <td>
                  {!! Field::form_input($si, $dt, ['name'=>'active', 'required'=>true, 'type'=>'select', 'options'=>$array_active], ['subtype'=>'multiple', 'subinput'=>'options', 'subcount'=>$key]) !!}
                  {!! Field::form_input($si, $dt, ['name'=>'name', 'type'=>'hidden', 'required'=>true], ['subtype'=>'multiple', 'subinput'=>'options', 'subcount'=>$key]) !!}
                  {!! Field::form_input($si, $dt, ['name'=>'id', 'type'=>'hidden', 'required'=>true], ['subtype'=>'multiple', 'subinput'=>'options', 'subcount'=>$key]) !!}
                </td>
              </tr>
            @endforeach
          @else
            <tr>
              <td class="table-counter" data-count="1">{{ 1 }}</td>
              <td>{!! Field::form_input(NULL, $dt, ['name'=>'label', 'required'=>true, 'type'=>'string'], ['label'=>'Nombre de Opción', 'cols'=>6, 'subtype'=>'multiple', 'subinput'=>'options', 'subcount'=>0]) !!}</td>
              <td>
                {!! Field::form_input(NULL, $dt, ['name'=>'active', 'required'=>true, 'type'=>'select', 'options'=>$array_active], ['subtype'=>'multiple', 'subinput'=>'options', 'subcount'=>0]) !!}
                {!! Field::form_input(NULL, $dt, ['name'=>'name', 'type'=>'hidden', 'required'=>true], ['subtype'=>'multiple', 'subinput'=>'options', 'subcount'=>0]) !!}
                {!! Field::form_input(NULL, $dt, ['name'=>'id', 'type'=>'hidden', 'required'=>true], ['subtype'=>'multiple', 'subinput'=>'options', 'subcount'=>0]) !!}
              </td>
            </tr>
          @endif
        </tbody>
        <tfoot>
          <tr><td colspan="3">
            <a class="agregar_fila" rel="options" href="#" data-count="500">+ Añadir otra fila</a>
          </td></tr>
        </tfoot>
      </table>
    </div>
  </div>
  <div class="row flex">
    {!! Field::form_input($i, $dt, ['name'=>'required', 'required'=>true, 'type'=>'select', 'options'=>[0=>'No obligatorio', 1=>'Obligatorio']], ['label'=>'¿Es un campo requerido?', 'cols'=>6]) !!}
    {!! Field::form_input($i, $dt, ['name'=>'tooltip', 'required'=>false, 'type'=>'string'], ['label'=>'Introduzca un tooltip', 'cols'=>6]) !!}
    {!! Field::form_input($i, $dt, ['name'=>'new_row', 'required'=>true, 'type'=>'select', 'options'=>[0=>'No, que se acomode donde corresponda', 1=>'Si, una nueva fila']], ['label'=>'¿Debe estar en una nueva fila?', 'cols'=>6]) !!}
    {!! Field::form_input(NULL, $dt, ['name'=>'cols', 'required'=>true, 'type'=>'select', 'options'=>[6=>'1/2 fila', 12=>'1 fila completa', 4=>'2/3 de fila', 3=>'1/4 de fila']], ['label'=>'¿Ancho del campo?', 'cols'=>6, 'value'=>$cols]) !!}
    {!! Field::form_input($field, $dt, ['name'=>'parent_id', 'type'=>'hidden'], ['label'=>NULL, 'cols'=>6]) !!}
    {!! Field::form_input(NULL, $dt, ['name'=>'field_id', 'type'=>'hidden'], ['label'=>NULL, 'cols'=>6, 'value'=>$field->id]) !!}
  </div>
  <div id="field_conditionals" class="child">
    <label>Condicionantes del Campo</label>
    <div class="table-responsive">
      <table class="table" id="conditionals">
        <thead><tr class="title">
          <td>Nº</td>
          <td>Campo Validador (*)</td>
          <td>Accíón de la Condicionante (*)</td>
          <td>Valor Condicionador (*)</td>
          <td>X</td>
        </tr></thead>
        <tbody>
          @if($action=='edit'&&count($i->field_conditionals)>0)
            @foreach($i->field_conditionals as $key => $si)
              <tr>
                <td class="table-counter" data-count="{{ $key+1 }}">{{ $key+1 }}</td>
                <td>{!! Field::form_input($si, $dt, ['name'=>'trigger_field', 'required'=>true, 'type'=>'select', 'options'=>$trigger_fields], ['label'=>'Nombre de Opción', 'cols'=>6, 'subtype'=>'multiple', 'subinput'=>'conditionals', 'subcount'=>$key]) !!}</td>
                <td>{!! Field::form_input($si, $dt, ['name'=>'trigger_show', 'required'=>true, 'type'=>'select', 'options'=>$trigger_actions], ['label'=>'Nombre de Opción', 'cols'=>6, 'subtype'=>'multiple', 'subinput'=>'conditionals', 'subcount'=>$key]) !!}</td>
                <?php $subarray = [];
                $sub_field = \Solunes\Master\App\Field::where('name', $si->trigger_field)->first();
                foreach(explode(',', $si->trigger_value) as $subfield){
                  $subarray[] = $sub_field->field_options()->where('name', $subfield)->first()->label;
                }
                $new_input = implode(',', $subarray); ?>
                <td>{!! Field::form_input(NULL, $dt, ['name'=>'trigger_value', 'required'=>true, 'type'=>'string'], ['label'=>'Nombre de Opción', 'cols'=>6, 'subtype'=>'multiple', 'subinput'=>'conditionals', 'subcount'=>$key, 'value'=>$new_input]) !!}</td>
                <td>
                  <a class="delete_row" rel="conditionals" href="#">X</a>
                  {!! Field::form_input($si, $dt, ['name'=>'id', 'type'=>'hidden', 'required'=>true], ['subtype'=>'multiple', 'subinput'=>'conditionals', 'subcount'=>$key]) !!}
                </td>
              </tr>
            @endforeach
          @else
            <tr>
              <td class="table-counter" data-count="1">{{ 1 }}</td>
              <td>{!! Field::form_input(NULL, $dt, ['name'=>'trigger_field', 'required'=>true, 'type'=>'select', 'options'=>$trigger_fields], ['label'=>'Nombre de Opción', 'cols'=>6, 'subtype'=>'multiple', 'subinput'=>'conditionals', 'subcount'=>0]) !!}</td>
              <td>{!! Field::form_input(NULL, $dt, ['name'=>'trigger_show', 'required'=>true, 'type'=>'select', 'options'=>$trigger_actions], ['label'=>'Nombre de Opción', 'cols'=>6, 'subtype'=>'multiple', 'subinput'=>'conditionals', 'subcount'=>0]) !!}</td>
              <td>{!! Field::form_input(NULL, $dt, ['name'=>'trigger_value', 'required'=>true, 'type'=>'string'], ['label'=>'Nombre de Opción', 'cols'=>6, 'subtype'=>'multiple', 'subinput'=>'conditionals', 'subcount'=>0]) !!}</td>
              <td>
                <a class="delete_row" rel="conditionals" href="#">X</a>
                {!! Field::form_input(NULL, $dt, ['name'=>'id', 'type'=>'hidden', 'required'=>true], ['subtype'=>'multiple', 'subinput'=>'conditionals', 'subcount'=>0]) !!}
              </td>
            </tr>
          @endif
        </tbody>
        <tfoot>
          <tr><td colspan="3">
            <a class="agregar_fila" rel="conditionals" href="#" data-count="500">+ Añadir otra fila</a>
          </td></tr>
        </tfoot>
      </table>
    </div>
  </div>
  {!! Field::form_submit($i, $model, $action) !!}
  {!! Form::close() !!}
@endsection
@section('script')
  @include('master::helpers.froala')
  @include('master::scripts.child-js')
  <script type="text/javascript">
  $(function () {
    applyCond();
    $("select#type").change(applyCond);
    }); 
    function isInArray(value, array) {
      return array.indexOf(value) > -1;
    }
    function applyCond() {
      // Generar array de valores a ser encontrados
      var trigger_array = [
        "select",
        "checkbox",
        "radio",
      ];
      var checked = 'false';
      var val = $("select#type").val();
      if(isInArray(val, trigger_array)){
        $("#field_options").slideDown(500);
      } else {
        $("#field_options").slideUp(500);
      }
    }
  </script>
  @include('master::scripts.upload-js')
  @include('master::scripts.map-js')
  @include('master::scripts.map-field-js')
  @include('master::scripts.lightbox-js')
  @include('master::scripts.tooltip-js')
  @include('master::scripts.accordion-js')
  @include('master::scripts.leave-form-js')
@endsection