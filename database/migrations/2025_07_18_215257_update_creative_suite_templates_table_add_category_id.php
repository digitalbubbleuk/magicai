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
        Schema::table('creative_suite_templates', function (Blueprint $table) {
            // Add category_id foreign key
            $table->unsignedBigInteger('category_id')->nullable()->after('name');
            $table->foreign('category_id')->references('id')->on('creative_suite_categories')->onDelete('set null');
            
            // Keep the old category column temporarily for data migration
            // We'll remove it in a separate migration after data is migrated
            $table->index('category_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('creative_suite_templates', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropIndex(['category_id']);
            $table->dropColumn('category_id');
        });
    }
};
