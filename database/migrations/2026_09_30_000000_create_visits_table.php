<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('visits', function (Blueprint $table) {
            $table->id();
            $table->string('ip_hash', 64)->nullable()->index();
            $table->string('path', 500)->index();
            $table->string('route_name', 100)->nullable();
            $table->string('method', 10)->default('GET');
            $table->string('referer', 1000)->nullable();
            $table->string('referer_host', 255)->nullable()->index();
            $table->string('country_code', 16)->default('UNKNOWN')->index();
            $table->string('country_name', 100)->default('Unknown');
            $table->string('device_type', 20)->default('desktop')->index();
            $table->string('browser', 50)->nullable();
            $table->string('platform', 50)->nullable();
            $table->string('locale', 10)->nullable();
            $table->timestamp('visited_at')->index();
            $table->timestamps();

            $table->index(['visited_at', 'path']);
            $table->index(['visited_at', 'country_code']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visits');
    }
};
