@extends('master::layouts/admin-2')

@section('content')
<h1 class="page-title"> Crear Conversación | <a href="{{ url('customer-admin/my-inbox') }}">Volver a Bandeja de Entrada</a></h1>

<div class="inbox">
  {!! Form::open(array('name'=>'create-inbox', 'id'=>'create', 'role'=>'form', 'url'=>'customer-admin/create-inbox', 'class'=>'form-horizontal prevent-double-submit', 'autocomplete'=>'off')) !!}
    <div class="row">
      <div class="col-sm-4">
        <div class="inbox-users right">
          <h3>Seleccionar Participantes</h3>
          @foreach($users as $key => $user)
            <div class="checkbox">
              <label><input type="checkbox" name="users[]" value="{{ $user->id }}">{{ $user->name }}</label>
            </div>
          @endforeach
        </div>
      </div>
      <div class="col-sm-5">
        <div class="inbox-messages create-message">
          <div class="reply">
            {{ \Form::textarea('message', NULL, ['rows'=>3, 'placeholder'=>'Escriba un mensaje...']) }}
            <div class="row">{!! \Field::form_input(NULL, 'create', $attachment_field, $attachment_field->extras) !!}</div>
            <input class="btn btn-site" type="submit" name="send" value="Crear Conversación">
          </div>
        </div>
      </div>
    </div>
  {!! Form::close() !!}
</div>
@endsection
@section('script')
  @include('master::scripts.upload-js')
  @include('master::scripts.tooltip-js')
@endsection