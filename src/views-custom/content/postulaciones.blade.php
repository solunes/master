@extends('layouts/master')
@include('helpers.meta')

@section('css')
  @include('helpers.page-css',['page'=>$page])
@endsection

@section('banner')
  <div class="top-banner">
    <h1 class="center">{{ strtoupper($page->name) }}</h1>
  </div>
@endsection

@section('content')

<div class="container content-page">
  <h4><a href="{{ url('auth/logout') }}"><i class="fa fa-sign-out"></i> Cerrar Sesión</a></h4>
  @if(count($registry_a)>0)
    <h3>A. Distinción a empresas ambientalmente sostenibles</h3>
    <table class="admin-table table table-striped table-bordered dt-responsive dataTable no-footer dtr-inline">
      <thead>
        <tr class="title">
          <td>Registro</td>
          <td>Fecha de Registro</td>
          <td>Estado de Registro</td>
          <td>Estado de Postulación</td>
        </tr>
      </thead>
      <tbody>
        @foreach($registry_a as $registry)
          <tr>
            <td>{{ $registry->name }}</td>
            <td>{{ $registry->created_at }}</td>
            <td>{{ trans('admin.'.$registry->status) }}</td>
            @if(count($registry->postulation_a)>0)
              @foreach($registry->postulation_a()->get()->take(1) as $postulation)
                <td>{{ trans('admin.'.$postulation->status) }}
                  @if($postulation->status=='holding')
                    | <a href="{{ url('postulacion-a?postulation_a='.$postulation->id) }}">Editar</a>
                  @endif
                </td>
              @endforeach
            @endif
          </tr>
        @endforeach
      </tbody>
    </table>
  @endif

  @if(count($registry_b)>0)
    <h3>B. Capital semilla a iniciativas sostenibles</h3>
    @foreach($registry_b as $registry)
      <h4>{{ $registry->name }}</h4>
      <p><strong>Fecha de Envío:</strong> {{ $registry->created_at }}</p>
      <p><strong>Estado de Registro:</strong> {{ trans('admin.'.$registry->status) }}</p>
      <p><strong><a href="{{ url('process/create-postulation-b/'.$registry->id) }}">Crear Nueva Propuesta</a></strong>
      <p><strong>Postulaciones Registradas:</strong> </p>
      @if(count($registry->postulation_b)>0)
        <table class="admin-table table table-striped table-bordered dt-responsive dataTable no-footer dtr-inline">
          <thead>
            <tr class="title">
              <td>Título de Propuesta</td>
              <td>Estado</td>
            </tr>
          </thead>
          <tbody>
            @foreach($registry->postulation_b as $postulation)
              <tr>
                <td>{{ $postulation->pb2 }}</td>
                <td>{{ trans('admin.'.$postulation->status) }}
                  @if($postulation->status=='holding')
                    | <a href="{{ url('postulacion-b?postulation_b='.$postulation->id) }}">Editar</a>
                  @endif
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      @else
      <p>Aún no envió ninguna propuesta.</p>
      @endif
    @endforeach
  @endif

</div>

@endsection
@section('script')

@endsection