@extends('master::layouts/admin')

@section('css')
  <link rel="stylesheet" href="{{ asset('assets/admin/css/froala.css') }}">
  @include('master::scripts.lightbox-css')
@endsection
@section('content')
  <h3><a href="{{ url('admin/form-list') }}">Admin de Formularios</a> | <a href="{{ url('admin/form/edit/'.$node->id) }}">Editar Propiedades de Formulario</a> | {{ $node->singular }}</h3>
  @include('master::includes.form')
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