<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<h2>{{ trans('mail.remind_password_title') }}</h2>

		<div>
			<p>{{ trans('mail.remind_password_content') }}:<br>{{ URL::to('password/reset', array($token)) }}.</p>
			<p>{{ trans('mail.remind_password_expire') }} {{ Config::get('auth.reminder.expire', 60) }} {{ trans('mail.remind_password_expire_2') }}</p>
			<p>{{ trans('mail.greetings') }}</p>
			<p>{{ trans('mail.signature') }}</p>
		</div>
	</body>
</html>
