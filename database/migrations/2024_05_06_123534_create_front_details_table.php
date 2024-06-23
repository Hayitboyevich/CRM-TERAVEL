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
        Schema::create('front_details', function (Blueprint $table) {
            $table->id();
            $table->enum('get_started_show', ['yes', 'no'])->default('yes')->nullable(false);
            $table->enum('sign_in_show', ['yes', 'no'])->default('yes')->nullable(false);
            $table->text('address')->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('email', 60)->nullable();
            $table->text('social_links')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->string('primary_color', 191)->nullable();
            $table->longText('custom_css')->nullable();
            $table->longText('custom_css_theme_two')->nullable();
            $table->string('locale', 191)->default('en')->nullable();
            $table->longText('contact_html')->nullable();
            $table->collation = 'utf8mb4_unicode_ci';
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('front_details');
    }
};
