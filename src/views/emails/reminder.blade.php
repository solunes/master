<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<h2>{{ trans('master::mail.remind_password_title') }}</h2>

		<div>
			<p>{{ trans('master::mail.remind_password_content') }}:<br>{{ URL::to('password/reset', array($token)) }}.</p>
			<p>{{ trans('master::mail.remind_password_expire') }} {{ Config::get('auth.reminder.expire', 60) }} {{ trans('master::mail.remind_password_expire_2') }}</p>
			<p>{{ trans('master::mail.greetings') }}</p>
			<p>{{ trans('master::mail.signature') }}</p>
		</div>
	</body>
</html>
