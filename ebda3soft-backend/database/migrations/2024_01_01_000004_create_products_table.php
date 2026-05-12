<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name_ar', 100);
            $table->string('name_en', 100)->nullable();
            $table->string('slug', 120)->unique();
            $table->string('icon', 100)->nullable();
            $table->foreignId('parent_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name_ar', 150);
            $table->string('name_en', 150)->nullable();
            $table->string('slug', 180)->unique();
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->text('short_desc_ar')->nullable();
            $table->text('short_desc_en')->nullable();
            $table->longText('full_desc_ar')->nullable();
            $table->longText('full_desc_en')->nullable();
            $table->string('thumbnail', 255)->nullable();
            $table->decimal('price', 12, 2)->nullable();
            $table->string('currency', 10)->default('YER');
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('product_features', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('feature_ar', 255);
            $table->string('feature_en', 255)->nullable();
            $table->string('icon', 100)->nullable();
            $table->integer('sort_order')->default(0);
        });

        Schema::create('product_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('image_path', 255);
            $table->string('alt_text', 200)->nullable();
            $table->boolean('is_primary')->default(false);
            $table->integer('sort_order')->default(0);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_images');
        Schema::dropIfExists('product_features');
        Schema::dropIfExists('products');
        Schema::dropIfExists('categories');
    }
};
