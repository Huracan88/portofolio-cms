<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('educations', function (Blueprint $table) {
            $table->id();
            $table->string('institution_es');
            $table->string('institution_en');
            $table->string('degree_es');
            $table->string('degree_en');
            $table->unsignedSmallInteger('started_at');
            $table->unsignedSmallInteger('ended_at')->nullable();
            $table->string('license_number')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('educations');
    }
};
