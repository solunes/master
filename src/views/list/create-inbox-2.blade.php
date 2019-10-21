@extends('master::layouts/admin-2')

@section('css')
  <link rel="stylesheet" href="{{ asset('assets/sales/store.css') }}">
@endsection

@section('content')
<div class="content-header-left col-md-9 col-12 mb-2">
  <div class="row breadcrumbs-top">
      <div class="col-12">
          <h2 class="content-header-title float-left mb-0">Crear Conversación</h2>
          <div class="breadcrumb-wrapper col-12">
              <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="{{ url(config('customer.redirect_after_login')) }}">Inicio</a></li>
                  <li class="breadcrumb-item"><a href="{{ url('customer-admin/my-inbox') }}">Inbox</a></li>
                  <li class="breadcrumb-item active">Crear Conversación
                  </li>
              </ol>
          </div>
      </div>
  </div>
</div>

{!! Form::open(array('name'=>'create-inbox', 'id'=>'create', 'role'=>'form', 'url'=>'customer-admin/create-inbox', 'class'=>'form-horizontal prevent-double-submit', 'autocomplete'=>'off')) !!}

  <!-- Vuesax Checkbox start -->
  <section class="vuesax-checkbox">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Primero, seleccione al contacto o los contactos con los que desea iniciar una conversación.</h4>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <!--<p>To add a checkBox, we have the <code>.vs-checkbox-con</code> as a wrapper. Also use <code>.vs-checkbox</code> for better output.</p>-->
                        <ul class="list-unstyled mb-0">
                          @foreach($users as $key => $user)
                            <li class="d-inline-block mr-2">
                                <fieldset>
                                    <div class="vs-checkbox-con vs-checkbox-primary">
                                        <input type="checkbox" name="users[]" value="{{ $user->id }}">
                                        <span class="vs-checkbox">
                                            <span class="vs-checkbox--check">
                                                <i class="vs-icon feather icon-check"></i>
                                            </span>
                                        </span>
                                        <span class="">{{ $user->name }}</span>
                                    </div>
                                </fieldset>
                            </li>
                          @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </section>
  <!-- Vuesax Checkbox end -->

  <!-- Basic Textarea start -->
  <section class="basic-textarea">
      <div class="row">
          <div class="col-12">
              <div class="card">
                  <div class="card-header">
                      <h4 class="card-title">Escribir Mensaje</h4>
                  </div>
                  <div class="card-content">
                      <div class="card-body">
                          <div class="row">
                              <div class="col-12">
                                  <fieldset class="form-group">
                                      {{ \Form::textarea('message', NULL, ['rows'=>3, 'class'=>'form-control', 'placeholder'=>'Escriba un mensaje...']) }}
                                  </fieldset>
                              </div>
                              <div class="col-12">
                                {!! \Field::form_input(NULL, 'create', $attachment_field, $attachment_field->extras) !!}
                                <br><br>
                                <button type="submit" class="btn btn-primary mr-1 mb-1">Crear Conversación</button>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </section>
  <!-- Basic Textarea end -->

{!! Form::close() !!}

@endsection
@section('script')
  @include('master::scripts.upload-js')
  @include('master::scripts.tooltip-js')
@endsection