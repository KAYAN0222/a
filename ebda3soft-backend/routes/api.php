<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\BranchController;
use App\Http\Controllers\Api\PublicController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\TicketController;
use App\Http\Controllers\Api\ChatbotController;
use App\Http\Controllers\Api\QuoteController;
use App\Models\Faq;
use App\Models\Setting;

// =====================================================
// المسارات العامة (بدون مصادقة)
// =====================================================

// المصادقة
Route::post('/auth/login', [AuthController::class, 'login']);

// الأنظمة/المنتجات
Route::get('/products',          [ProductController::class, 'index']);
Route::get('/products/{slug}',   [ProductController::class, 'show']);
Route::get('/categories',        [ProductController::class, 'categories']);

// الأخبار والمقالات
Route::get('/posts',             [PostController::class, 'index']);
Route::get('/posts/{slug}',      [PostController::class, 'show']);

// الفروع
Route::get('/branches',          [BranchController::class, 'index']);

// FAQs + Settings
Route::get('/faqs', fn() => response()->json(Faq::where('is_active',true)->orderBy('sort_order')->get()));
Route::get('/settings', fn() => response()->json(Setting::all()->keyBy('key')->map->value));

// النماذج العامة
Route::post('/contact',          [PublicController::class, 'contact']);
Route::post('/request-system',   [PublicController::class, 'requestSystem']);
Route::post('/apply-job',        [PublicController::class, 'applyJob']);

// ===== تذاكر الدعم الفني (Support Tickets) =====
Route::post('/tickets',                          [TicketController::class, 'store']);
Route::get('/tickets/track/{number}',            [TicketController::class, 'track']);
Route::post('/tickets/{ticket}/reply',           [TicketController::class, 'reply']);

// ===== Chatbot =====
Route::post('/chatbot',                          [ChatbotController::class, 'chat']);

// ===== طلبات عروض الأسعار (Quotes) =====
Route::post('/quotes',                           [QuoteController::class, 'store']);

// =====================================================
// المسارات المحمية (تتطلب مصادقة)
// =====================================================
Route::middleware('auth:sanctum')->group(function () {

    Route::get('/auth/me',      [AuthController::class, 'me']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);

    // إدارة الأنظمة
    Route::post('/products',              [ProductController::class, 'store']);
    Route::put('/products/{product}',     [ProductController::class, 'update']);
    Route::delete('/products/{product}',  [ProductController::class, 'destroy']);

    // إدارة الأخبار
    Route::post('/posts',                 [PostController::class, 'store']);
    Route::put('/posts/{post}',           [PostController::class, 'update']);
    Route::delete('/posts/{post}',        [PostController::class, 'destroy']);

    // إدارة الفروع
    Route::post('/branches',              [BranchController::class, 'store']);
    Route::put('/branches/{branch}',      [BranchController::class, 'update']);
    Route::delete('/branches/{branch}',   [BranchController::class, 'destroy']);

    // إدارة التذاكر (للموظفين)
    Route::get('/tickets',                           [TicketController::class, 'index']);
    Route::get('/tickets/{ticket}',                  [TicketController::class, 'show']);
    Route::patch('/tickets/{ticket}/status',         [TicketController::class, 'updateStatus']);

    // إدارة Chatbot FAQs
    Route::get('/chatbot/faqs',                      [ChatbotController::class, 'faqs']);
    Route::post('/chatbot/faqs',                     [ChatbotController::class, 'storeFaq']);
    Route::put('/chatbot/faqs/{faq}',                [ChatbotController::class, 'updateFaq']);
    Route::delete('/chatbot/faqs/{faq}',             [ChatbotController::class, 'deleteFaq']);

    // إدارة طلبات الأسعار
    Route::get('/quotes',                            [QuoteController::class, 'index']);
    Route::patch('/quotes/{quote}',                  [QuoteController::class, 'update']);

    // لوحة الإدارة
    Route::prefix('admin')->group(function () {
        Route::get('/dashboard',                         [AdminController::class, 'dashboard']);
        Route::get('/analytics',                         [AdminController::class, 'analytics']);
        Route::get('/settings',                          [AdminController::class, 'getSettings']);
        Route::post('/settings',                         [AdminController::class, 'updateSettings']);
        Route::get('/messages',                          [AdminController::class, 'messages']);
        Route::patch('/messages/{message}/read',         [AdminController::class, 'markMessageRead']);
        Route::get('/job-applications',                  [AdminController::class, 'jobApplications']);
        Route::patch('/job-applications/{job}/status',   [AdminController::class, 'updateJobStatus']);
        Route::get('/orders',                            [AdminController::class, 'orders']);
        Route::patch('/orders/{order}/status',           [AdminController::class, 'updateOrderStatus']);
    });
});
