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
        Schema::create('creative_suite_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('category', 100)->index();
            $table->string('preview_image', 500);
            $table->integer('stage_width');
            $table->integer('stage_height');
            $table->json('template_data');
            $table->json('tags')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->integer('usage_count')->default(0)->index();
            $table->timestamps();
            
            // Add indexes for better performance
            $table->index(['category', 'is_active']);
            $table->index(['usage_count', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('creative_suite_templates');
    }
};
