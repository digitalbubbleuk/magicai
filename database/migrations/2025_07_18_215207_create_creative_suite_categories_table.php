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
        Schema::create('creative_suite_categories', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // Unique identifier for translations (e.g., 'social_media')
            $table->string('name'); // Default English name
            $table->string('slug')->unique(); // URL-friendly slug
            $table->text('description')->nullable(); // Category description
            $table->string('icon')->nullable(); // Icon class or path
            $table->string('color', 7)->nullable(); // Hex color code
            $table->integer('sort_order')->default(0); // Display order
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['is_active', 'sort_order']);
            $table->index('key');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('creative_suite_categories');
    }
};
