<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('profile_physical_attributes', function (Blueprint $table) {
            $table->string('body_type')->nullable()->after('ethnicity');
        });
    }

    public function down(): void
    {
        Schema::table('profile_physical_attributes', function (Blueprint $table) {
            $table->dropColumn('body_type');
        });
    }
};
