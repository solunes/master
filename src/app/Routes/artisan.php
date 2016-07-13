<?php

Route::get('artisan/deploy', function () {
  Artisan::call('deploy');
  return dd(Artisan::output());
});

Route::get('artisan/migrate', function () {
  Artisan::call('migrate:reset');
  Artisan::call('migrate');
  Artisan::call('db:seed');
  return dd(Artisan::output());
});

Route::get('artisan/seed', function () {
  Artisan::call('seed');
  return dd(Artisan::output());
});

Route::get('artisan/generate-nodes', function () {
  Artisan::call('generate-nodes');
  return dd(Artisan::output());
});

Route::get('artisan/import-excel', function () {
  Artisan::call('import-excel');
  return dd(Artisan::output());
});