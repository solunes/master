@extends('master::layouts/email')

@section('content')
	<h2 style="font-family: Arial, Helvetica, sans-serif;margin-top: 16px;margin-bottom: 8px;word-break: break-word;font-size: 28px;line-height: 38px;font-weight: bold;">
		{{ $msg }}
	</h2>
	<p style="font-family: Arial, Helvetica, sans-serif;margin-top: 0px;margin-bottom: 32px;word-break: break-word;font-size: 19px;line-height: 31px;">
		{{ $msg }}
	</p>
	@include('master::emails.helpers.button', ['button_link'=>$button_link, 'button_title'=>$button_title])
@endsection

@section('unsuscribe-email')
	{{ url('auth/unsuscribe/'.urlencode($email)) }}
@endsection