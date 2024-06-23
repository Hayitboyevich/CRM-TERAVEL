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
        Schema::create('tr_front_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('language_setting_id')->nullable();
            $table->string('header_title', 200)->nullable(false);
            $table->text('header_description')->nullable(false);
            $table->string('image', 200)->nullable(false);
            $table->string('feature_title', 191)->nullable();
            $table->string('feature_description', 191)->nullable();
            $table->string('price_title', 191)->nullable();
            $table->string('price_description', 191)->nullable();
            $table->string('task_management_title', 191)->nullable();
            $table->text('task_management_detail')->nullable();
            $table->string('manage_bills_title', 191)->nullable();
            $table->text('manage_bills_detail')->nullable();
            $table->string('teamates_title', 191)->nullable();
            $table->text('teamates_detail')->nullable();
            $table->string('favourite_apps_title', 191)->nullable();
            $table->text('favourite_apps_detail')->nullable();
            $table->string('cta_title', 191)->nullable();
            $table->text('cta_detail')->nullable();
            $table->string('client_title', 191)->nullable();
            $table->text('client_detail')->nullable();
            $table->string('testimonial_title', 191)->nullable();
            $table->text('testimonial_detail')->nullable();
            $table->string('faq_title', 191)->nullable();
            $table->text('faq_detail')->nullable();
            $table->text('footer_copyright_text')->nullable();
            $table->timestamps();

            $table->foreign('language_setting_id')->references('id')->on('language_settings')->onDelete('cascade')->onUpdate('cascade');
            $table->collation = 'utf8mb4_unicode_ci';
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tr_front_details');
    }
};
