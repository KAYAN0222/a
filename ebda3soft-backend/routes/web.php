<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
    Route::get('/run-migration', function () {
    \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
    return 'تم إنشاء جداول قاعدة البيانات بنجاح يا أحمد!';
});
