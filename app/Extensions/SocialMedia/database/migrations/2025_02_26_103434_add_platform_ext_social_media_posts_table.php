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
        Schema::table('ext_social_media_posts', function (Blueprint $table) {
            if (Schema::hasColumn('ext_social_media_posts', 'social_media_platform')) {
                return;
            }

            $table->string('social_media_platform')
                ->nullable()
                ->after('social_media_platform_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ext_social_media_posts', function (Blueprint $table) {
            $table->dropColumn('social_media_platform');
        });
    }
};
