<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('original_name', 255);
            $table->string('file_name', 255);
            $table->string('disk', 50)->default('public');
            $table->string('path', 500)->unique();
            $table->string('mime_type', 100);
            $table->string('extension', 10);
            $table->unsignedBigInteger('size');
            $table->unsignedInteger('width')->nullable();
            $table->unsignedInteger('height')->nullable();
            $table->string('alt_es', 255)->nullable();
            $table->string('alt_en', 255)->nullable();
            $table->string('collection', 100)->nullable()->index();
            $table->softDeletes()->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};
