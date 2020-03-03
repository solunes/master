@extends('master::layouts/email')

@section('icon', 'Lock')

@section('content')
	<h2 style="font-family: Arial, Helvetica, sans-serif;margin-top: 16px;margin-bottom: 8px;word-break: break-word;font-size: 28px;line-height: 38px;font-weight: bold;">
		Recuperar Contraseña
	</h2>
	<p style="font-family: Arial, Helvetica, sans-serif;margin-top: 0px;margin-bottom: 32px;word-break: break-word;font-size: 19px;line-height: 31px;">
		{{ trans('master::mail.remind_password_content') }}
	</p>
	<p style="font-family: Arial, Helvetica, sans-serif;margin-top: 0px;margin-bottom: 32px;word-break: break-word;font-size: 19px;line-height: 31px;">
		{{ trans('master::mail.remind_password_expire') }} {{ Config::get('auth.reminder.expire', 60) }} {{ trans('master::mail.remind_password_expire_2') }}
	</p>
	@include('master::emails.helpers.button', ['button_link'=>URL::to('password/reset', array($token)), 'button_title'=>'Recuperar Contraseña'])
@endsection

@section('unsuscribe-email')
	{{ url('auth/unsuscribe/'.urlencode($email)) }}
@endsection