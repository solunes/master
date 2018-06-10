@extends('master::layouts/admin')

@section('content')
<h1 class="page-title"> Ver Conversación | <a href="{{ url('admin/my-inbox') }}">Volver a Bandeja de Entrada</a></h1>

<div class="inbox">
  <div class="row">
    <div class="col-sm-3">
      <div class="inbox-users right">
        <h3>Participantes de la Conversación</h3>
        @foreach($users as $key => $user)
          <a href="#">
            <div class="inbox-user">
              <img class="pull-right" src="{{ asset('assets/admin/img/no_picture.jpg') }}" width="27px" height="27px">
              <div class="inbox-user-title"> {{ $user->user->name }} </div>
            </div>
          </a>
        @endforeach
      </div>
    </div>
    <div class="col-sm-9">
      <div class="inbox-messages">
        <div class="reply">
          {!! Form::open(array('name'=>'inbox-reply', 'id'=>'reply', 'role'=>'form', 'url'=>'admin/inbox-reply', 'class'=>'form-horizontal prevent-double-submit', 'autocomplete'=>'off')) !!}
            {{ \Form::textarea('message', NULL, ['rows'=>3, 'placeholder'=>'Escriba un mensaje...']) }}
            <div class="row">{!! \Field::form_input(NULL, 'create', $attachment_field, $attachment_field->extras) !!}</div>
            <input type="hidden" name="parent_id" value="{{ $inbox->id }}">
            <input class="btn btn-site" type="submit" name="send" value="Enviar Mensaje">
          {!! Form::close() !!}
        </div>
        @foreach($items as $key => $item)
          <div class="inbox-message @if($item->user_id!=$user_id) right @else left @endif ">
            <a href="#">
              <div class="inbox-user">
                <img class=" @if($item->user_id!=$user_id) pull-right @else pull-left @endif " src="{{ asset('assets/admin/img/no_picture.jpg') }}" width="27px" height="27px">
                <div class="inbox-user-title"> {{ $item->user->name }} </div>
              </div>
            </a>
            <div class="inbox-view-message"> {!! nl2br($item->message) !!} </div>
            @if($item->attachments&&count(json_decode($item->attachments), true)>0)
              <div class="inbox-view-files">
                <p><strong>Documentos Adjuntos:</strong><br>
                @foreach(json_decode($item->attachments, true) as $attachment)
                  <br>- <a href="{!! asset(\Asset::get_file('inbox', $attachment)) !!}">{{ $attachment }}</a>
                @endforeach
                </p>
              </div>
            @endif
            <div class="inbox-view-controls">
              <span class="inbox-view-date">
                <i class="fa fa-calendar"></i> {{ $item->created_at->format('d/m/Y H:i') }}
              </span>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </div>
</div>
@endsection
@section('script')
  @include('master::scripts.tooltip-js')
  @include('master::scripts.upload-js')
@endsection