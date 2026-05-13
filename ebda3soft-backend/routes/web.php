<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

Route::get('/', function () {
    return view('welcome'); // أو صفحة تسجيل الدخول الخاصة بمشروعك
}); // <-- هذا هو القوس الذي كان مفقوداً في كودك

Route::get('/super-fix', function () {
    try {
        $output = "";
        
        // 1. مسح جميع الملفات المؤقتة والتكوينات التي قد تسبب تعارضاً مع الكود الجديد
        Artisan::call('optimize:clear');
        $output .= "✅ تم تنظيف الكاش والإعدادات بنجاح.<br>";

        // 2. تشغيل الميغريشن الإجباري
        Artisan::call('migrate', ['--force' => true]);
        $output .= "✅ تمت الميغريشن بنجاح!<br>";
        
        // 3. عرض المخرجات التفصيلية
        $output .= nl2br(Artisan::output());

        return $output;

    } catch (\Throwable $e) {
        // اصطياد جميع أنواع الأخطاء (Exceptions و Fatal Errors)
        return "❌ فشل جذري بسبب:<br> " . 
               "الرسالة: " . $e->getMessage() . "<br>" .
               "في الملف: " . $e->getFile() . "<br>" .
               "السطر: " . $e->getLine();
    }
});

// هنا تبدأ بإضافة مسارات مشروع "إبداع سوفت" الفعلية
