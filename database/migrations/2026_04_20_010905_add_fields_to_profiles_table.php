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
        Schema::table('profiles', function (Blueprint $table) {
            $table->enum('gender', ['masculino', 'feminino', 'trans', 'outros'])->nullable()->after('age');
            $table->string('telegram')->nullable()->after('description');
            $table->string('tagline')->nullable()->after('telegram');
            $table->json('attendance_target')->nullable()->after('tagline');
            $table->json('payment_methods')->nullable()->after('attendance_target');
            $table->boolean('documents_verified')->default(false)->after('payment_methods');
            $table->boolean('no_reports')->default(false)->after('documents_verified');
            $table->boolean('clean_history')->default(false)->after('no_reports');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->dropColumn([
                'gender',
                'telegram',
                'tagline',
                'attendance_target',
                'payment_methods',
                'documents_verified',
                'no_reports',
                'clean_history'
            ]);
        });
    }
};
