<?php

Route::get('artisan/deploy', function () {
  Artisan::call('deploy');
  return dd(Artisan::output());
});

Route::get('artisan/seed', function () {
  Artisan::call('seed');
  return dd(Artisan::output());
});

Route::get('artisan/generate-translations', function () {
  Artisan::call('generate-translations');
  return dd(Artisan::output());
});

Route::get('artisan/backup-save', function () {
  Artisan::call('backup-save');
  return dd(Artisan::output());
});

Route::get('artisan/test-system', function () {
  Artisan::call('test-system');
  return dd(Artisan::output());
});

Route::get('artisan/webp-all-files', function () {
  Artisan::call('webp-all-files');
  return dd(Artisan::output());
});