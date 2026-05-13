<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

// الصفحة الرئيسية
Route::get('/', function () {
    return "مرحباً بك  ، مشروع softpro يعمل!";
});

// أمر الميغريشن الإجباري
Route::get('/run-migration', function () {
    try {
        Artisan::call('migrate', ['--force' => true]);
        return "تم إنشاء الجداول بنجاح!";
    } catch (\Exception $e) {
        // إظهار الخطأ الحقيقي مهما كان
        return "فشل بسبب: " . $e->getMessage();
    }
});
