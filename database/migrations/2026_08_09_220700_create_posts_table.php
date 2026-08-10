<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('author_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('category_id')->nullable()->constrained()->restrictOnDelete();
            $table->string('title_es', 300);
            $table->string('title_en', 300);
            $table->string('slug', 350)->unique();
            $table->string('excerpt_es', 500)->nullable();
            $table->string('excerpt_en', 500)->nullable();
            $table->longText('body_es')->nullable();
            $table->longText('body_en')->nullable();
            $table->string('cover_image_url', 500)->nullable();
            $table->boolean('is_published')->default(false)->index();
            $table->boolean('is_featured')->default(false)->index();
            $table->timestamp('published_at')->nullable()->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
