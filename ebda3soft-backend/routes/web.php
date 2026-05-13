<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

// المسار الرئيسي
Route::get('/', function () {
    return view('welcome');
});

// مسار الإصلاح الجذري والميغريشن
Route::get('/super-fix', function () {
    try {
        Artisan::call('optimize:clear');
        Artisan::call('migrate', ['--force' => true]);
        
        return "✅ تم تنظيف الكاش وإنشاء الجداول بنجاح!<br><br>" . nl2br(Artisan::output());
    } catch (\Throwable $e) {
        return "❌ فشل بسبب: " . $e->getMessage() . " في السطر " . $e->getLine();
    }
});
