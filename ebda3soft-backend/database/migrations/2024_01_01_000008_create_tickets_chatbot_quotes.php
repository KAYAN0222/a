<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // ===== نظام تذاكر الدعم الفني (Support Tickets) =====
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number', 20)->unique();
            $table->string('subject', 200);
            $table->text('description');
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('client_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->string('requester_name', 100);
            $table->string('requester_email', 150)->nullable();
            $table->string('requester_phone', 30)->nullable();
            $table->enum('priority', ['low', 'medium', 'high', 'critical'])->default('medium');
            $table->enum('status', ['open', 'in_progress', 'waiting', 'resolved', 'closed'])->default('open');
            $table->string('attachment', 255)->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
        });

        Schema::create('ticket_replies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('author_name', 100)->nullable();
            $table->text('message');
            $table->boolean('is_staff')->default(false);
            $table->string('attachment', 255)->nullable();
            $table->timestamps();
        });

        // ===== Chatbot - أسئلة وأجوبة ذكية =====
        Schema::create('chatbot_faqs', function (Blueprint $table) {
            $table->id();
            $table->string('question', 255);
            $table->text('answer');
            $table->json('keywords');       // كلمات مفتاحية للمطابقة
            $table->string('category', 80)->default('general');
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('chatbot_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('session_id', 80)->unique();
            $table->json('messages');       // كل رسائل المحادثة
            $table->string('user_name', 100)->nullable();
            $table->string('user_phone', 30)->nullable();
            $table->boolean('escalated')->default(false); // تحويل لدعم بشري
            $table->timestamps();
        });

        // ===== نظام طلب عروض الأسعار (Quotes) المتقدم =====
        Schema::create('quotes', function (Blueprint $table) {
            $table->id();
            $table->string('quote_number', 30)->unique();
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->string('company_name', 150);
            $table->string('contact_name', 100);
            $table->string('phone', 30);
            $table->string('email', 150)->nullable();
            $table->string('city', 80)->nullable();
            $table->integer('employees_count')->nullable();
            $table->text('requirements');
            $table->string('attachment', 255)->nullable();
            $table->enum('status', ['new', 'reviewing', 'quoted', 'accepted', 'rejected'])->default('new');
            $table->text('admin_notes')->nullable();
            $table->decimal('quoted_price', 12, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quotes');
        Schema::dropIfExists('chatbot_sessions');
        Schema::dropIfExists('chatbot_faqs');
        Schema::dropIfExists('ticket_replies');
        Schema::dropIfExists('tickets');
    }
};
