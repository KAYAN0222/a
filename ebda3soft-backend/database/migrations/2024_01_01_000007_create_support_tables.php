<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('faqs', function (Blueprint $table) {
            $table->id();
            $table->string('question_ar', 255);
            $table->text('answer_ar');
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('contact_messages', function (Blueprint $table) {
            $table->id();
            $table->string('full_name', 100);
            $table->string('email', 150)->nullable();
            $table->string('phone', 30)->nullable();
            $table->string('subject', 200)->nullable();
            $table->text('message');
            $table->boolean('is_read')->default(false);
            $table->timestamp('replied_at')->nullable();
            $table->timestamps();
        });

        Schema::create('job_applications', function (Blueprint $table) {
            $table->id();
            $table->string('full_name', 100);
            $table->string('email', 150)->nullable();
            $table->string('phone', 30);
            $table->string('position', 100)->nullable();
            $table->unsignedInteger('experience_years')->nullable();
            $table->string('cv_path', 255)->nullable();
            $table->text('cover_letter')->nullable();
            $table->enum('status', ['new', 'reviewed', 'interviewed', 'hired', 'rejected'])->default('new');
            $table->timestamps();
        });

        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key', 100)->unique();
            $table->text('value')->nullable();
            $table->string('type', 30)->default('text');
        });

        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('action', 100)->nullable();
            $table->string('model_type', 100)->nullable();
            $table->unsignedBigInteger('model_id')->nullable();
            $table->json('old_data')->nullable();
            $table->json('new_data')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
        Schema::dropIfExists('settings');
        Schema::dropIfExists('job_applications');
        Schema::dropIfExists('contact_messages');
        Schema::dropIfExists('faqs');
    }
};
