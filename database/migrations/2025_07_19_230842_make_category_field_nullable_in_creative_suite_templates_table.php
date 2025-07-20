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
            // Make the old category field nullable so it doesn't require a value
            $table->string('category', 100)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('creative_suite_templates', function (Blueprint $table) {
            //
        });
    }
};
