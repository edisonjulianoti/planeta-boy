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
        Schema::create('subscriber_category_restricted_services', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('subscriber_category_id');
            $table->unsignedBigInteger('service_id');
            $table->timestamps();

            $table->foreign('subscriber_category_id', 'scrs_sub_cat_id')->references('id')->on('subscriber_categories')->onDelete('cascade');
            $table->foreign('service_id', 'scrs_service_id')->references('id')->on('services')->onDelete('cascade');

            $table->unique(['subscriber_category_id', 'service_id'], 'scrs_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriber_category_restricted_services');
    }
};
