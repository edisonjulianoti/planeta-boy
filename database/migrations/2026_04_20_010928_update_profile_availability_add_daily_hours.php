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
        Schema::table('profile_availability', function (Blueprint $table) {
            $table->time('monday_start')->nullable()->after('end_time');
            $table->time('monday_end')->nullable()->after('monday_start');
            $table->time('tuesday_start')->nullable()->after('monday_end');
            $table->time('tuesday_end')->nullable()->after('tuesday_start');
            $table->time('wednesday_start')->nullable()->after('tuesday_end');
            $table->time('wednesday_end')->nullable()->after('wednesday_start');
            $table->time('thursday_start')->nullable()->after('wednesday_end');
            $table->time('thursday_end')->nullable()->after('thursday_start');
            $table->time('friday_start')->nullable()->after('thursday_end');
            $table->time('friday_end')->nullable()->after('friday_start');
            $table->time('saturday_start')->nullable()->after('friday_end');
            $table->time('saturday_end')->nullable()->after('saturday_start');
            $table->time('sunday_start')->nullable()->after('saturday_end');
            $table->time('sunday_end')->nullable()->after('sunday_start');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profile_availability', function (Blueprint $table) {
            $table->dropColumn([
                'monday_start', 'monday_end',
                'tuesday_start', 'tuesday_end',
                'wednesday_start', 'wednesday_end',
                'thursday_start', 'thursday_end',
                'friday_start', 'friday_end',
                'saturday_start', 'saturday_end',
                'sunday_start', 'sunday_end'
            ]);
        });
    }
};
