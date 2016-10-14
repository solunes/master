<?php

Route::get('artisan/deploy', function () {
  Artisan::call('deploy');
  return dd(Artisan::output());
});

Route::get('artisan/seed', function () {
  Artisan::call('seed');
  return dd(Artisan::output());
});