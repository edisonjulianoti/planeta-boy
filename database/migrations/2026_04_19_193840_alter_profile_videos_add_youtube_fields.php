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
        Schema::table('profile_videos', function (Blueprint $table) {
            $table->string('video_id')->nullable()->after('url');
            $table->string('platform')->default('youtube')->after('video_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profile_videos', function (Blueprint $table) {
            $table->dropColumn(['video_id', 'platform']);
        });
    }
};
