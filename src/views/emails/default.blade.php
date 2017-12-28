@extends('master::layouts/email')

@section('content')
	{!! $msg !!}
@endsection

@section('unsuscribe-email')
	{{ url('auth/unsuscribe/'.urlencode($email)) }}
@endsection