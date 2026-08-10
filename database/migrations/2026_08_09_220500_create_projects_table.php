<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('title_es', 200);
            $table->string('title_en', 200);
            $table->string('slug', 250)->unique();
            $table->string('excerpt_es', 500)->nullable();
            $table->string('excerpt_en', 500)->nullable();
            $table->longText('description_es')->nullable();
            $table->longText('description_en')->nullable();
            $table->string('image_url', 500)->nullable();
            $table->string('project_url', 500)->nullable();
            $table->string('repo_url', 500)->nullable();
            $table->boolean('is_featured')->default(false)->index();
            $table->boolean('is_visible')->default(true)->index();
            $table->timestamp('published_at')->nullable()->index();
            $table->unsignedInteger('sort_order')->default(0)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
