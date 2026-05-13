<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome'); // أو صفحة تسجيل الدخول الخاصة بمشروعك
use Illuminate\Support\Facades\Artisan;

Route::get('/run-migration', function () {
    try {
        Artisan::call('migrate', ['--force' => true]);
        return "تم تحديث جداول إبداع سوفت بنجاح!";
    } catch (\Exception $e) {
        return "فشل التحديث بسبب: " . $e->getMessage();
    }
});

// هنا تبدأ بإضافة مسارات مشروع "إبداع سوفت" الفعلية
