<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add thumbnail path to profile_images
        Schema::table('profile_images', function (Blueprint $table) {
            $table->string('thumb_path')->nullable()->after('url');
        });

        // Add local video support to profile_videos
        Schema::table('profile_videos', function (Blueprint $table) {
            $table->string('path')->nullable()->after('video_id');
            $table->string('type')->default('youtube')->after('platform');
        });
    }

    public function down(): void
    {
        Schema::table('profile_images', function (Blueprint $table) {
            $table->dropColumn('thumb_path');
        });

        Schema::table('profile_videos', function (Blueprint $table) {
            $table->dropColumn(['path', 'type']);
        });
    }
};
