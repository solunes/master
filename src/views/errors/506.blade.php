@extends('master::layouts/error')

@section('title')
    {{ trans('master::admin.506_title') }}
@endsection

@section('description')
    {{ trans('master::admin.506_description') }}:<br><br><strong>{!! $message !!}</strong>
@endsection