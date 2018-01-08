@extends('master::layouts/email')

@section('icon')
Profile
@endsection

@section('content')
	<h2 style="font-family: Arial, Helvetica, sans-serif;margin-top: 16px;margin-bottom: 8px;word-break: break-word;font-size: 28px;line-height: 38px;font-weight: bold;">
		{{ trans('master::mail.verify_email_title') }}
	</h2>
	<p style="font-family: Arial, Helvetica, sans-serif;margin-top: 0px;margin-bottom: 32px;word-break: break-word;font-size: 19px;line-height: 31px;">
		{{ trans('master::mail.verify_email_text') }}
	</p>
	<p style="font-family: Arial, Helvetica, sans-serif;margin-top: 0px;margin-bottom: 32px;word-break: break-word;font-size: 19px;line-height: 31px;">
		{{ trans('master::mail.verify_email_instruction') }}
	</p>
	@include('master::emails.helpers.button', ['button_link'=>$confirmation_url, 'button_title'=>trans('master::mail.verify_email_button')])
@endsection

@section('unsuscribe-email')
	{{ url('auth/unsuscribe/'.urlencode($email)) }}
@endsection