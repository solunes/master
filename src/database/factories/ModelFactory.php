<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\Customer::class, function ($faker) {
    return ['name'=>$faker->company];
});

$factory->define(App\CustomerPoint::class, function ($faker) {
	$date =$faker->date($format='Y-m-d', $min = '-90 days', $max = 'now');
    return ['customer_id'=>rand(1,30), 'city_id'=>rand(1,4), 'name'=>$faker->company, 'assigned_staff'=>rand(1,10), 'contract_signed'=>$date, 'contract_duration'=>rand(1,150)];
});

$factory->define(App\Operator::class, function ($faker) {
    return ['name'=>$faker->name, 'ci'=>$faker->unixTime];
});

$factory->define(App\OperatorAttendance::class, function ($faker) {
	$date = $faker->dateTimeBetween($startDate = '-90 days', $endDate = 'now');
	$time_1 = $faker->dateTimeBetween($startDate = '-3 hours', $endDate = 'now')->format('H:i:s');
	$time_2 = $faker->dateTimeBetween($startDate = 'now', $endDate = '+3 hours')->format('H:i:s');
    return ['point_id'=>rand(1,150), 'date'=>$date->format('Y-m-d'), 'registered_in'=>$time_1, 'registered_out'=>$time_2, 'created_at'=>$date->format('Y-m-d H:i:s')];
});

$factory->define(App\FormVer::class, function ($faker) {
    return ['name'=>$faker->name];
});

$factory->define(App\FormField::class, function ($faker) {
    return ['form_id'=>rand(1,2), 'field_type'=>'integer', 'type'=>'score', 'name'=>$faker->name];
});

$factory->define(App\FilledForm::class, function ($faker) {
	$date = $faker->dateTimeBetween($startDate = '-90 days', $endDate = 'now');
    return ['point_id'=>rand(1,50), 'user_id'=>1, 'score'=>rand(1,5), 'created_at'=>$date->format('Y-m-d H:i:s')];
});

$factory->define(App\FilledField::class, function ($faker) {
    return ['integer'=>rand(1,5)];
});

$factory->define(App\Product::class, function ($faker) {
    return ['code'=>$faker->randomNumber($nbDigits = 7) , 'name'=>$faker->name, 'unit'=>$faker->name];
});

/*$factory->define(App\Customer::class, function ($faker) {
	$name = $faker->firstName;
	$last_name = $faker->lastName;
	$full_name = $last_name.' '.$name;
    return ['name'=>$name, 'surname'=>$last_name, 'full_name'=>$full_name, 'email'=>$faker->freeEmail, 'document_type_id'=>rand(1,3), 'document_number'=>rand(100000,999999), 'document_expiration'=>$faker->date($format = 'Y-m-d', $min = '2 years', $max = '5 years'), 'country_id'=>rand(1,150), 'phone'=>$faker->phoneNumber, 'born_date'=>$faker->date($format = 'Y-m-d', $max = '-30 years'), 'observations'=>$faker->paragraph($nbSentences  = 2)];
});

$factory->define(App\Supplier::class, function ($faker) {
    return ['name'=>$faker->name, 'email'=>$faker->freeEmail, 'code'=>$s = substr(str_shuffle(str_repeat("ABCDEFGHIJKLMNOPQRSTUVWXYZ",3)),0,3), 'phone'=>$faker->phoneNumber, 'city'=>$faker->city, 'address'=>$faker->streetAddress, 'observations'=>$faker->paragraph($nbSentences=2)];
});

$factory->define(App\Sale::class, function ($faker) {
    return ['code'=>$faker->ean8, 'arrival_date'=>$faker->date($format='Y-m-d', $min='now', $max = '+1 year'), 'arrival_time'=>$faker->time($format='H:i:s'), 'departure_date'=>$faker->date($format='Y-m-d', $min='now', $max = '+1 year'), 'departure_time'=>$faker->time($format='H:i:s')];
});*/