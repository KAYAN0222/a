<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->string('name_ar', 80);
            $table->string('slug', 100)->unique();
        });

        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title_ar', 255);
            $table->string('title_en', 255)->nullable();
            $table->string('slug', 280)->unique();
            $table->longText('content_ar')->nullable();
            $table->longText('content_en')->nullable();
            $table->text('excerpt_ar')->nullable();
            $table->string('thumbnail', 255)->nullable();
            $table->enum('post_type', ['news', 'blog', 'event'])->default('news');
            $table->foreignId('author_id')->nullable()->constrained('users')->nullOnDelete();
            $table->boolean('is_published')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->unsignedInteger('views_count')->default(0);
            $table->timestamps();
        });

        Schema::create('post_tags', function (Blueprint $table) {
            $table->foreignId('post_id')->constrained()->cascadeOnDelete();
            $table->foreignId('tag_id')->constrained()->cascadeOnDelete();
            $table->primary(['post_id', 'tag_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('post_tags');
        Schema::dropIfExists('posts');
        Schema::dropIfExists('tags');
    }
};
