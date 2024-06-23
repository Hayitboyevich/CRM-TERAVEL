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
        Schema::create('faq_files', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id')->nullable(false);
            $table->unsignedBigInteger('faq_id')->nullable(false);
            $table->string('filename', 191)->nullable(false);
            $table->text('description')->nullable();
            $table->string('google_url', 191)->nullable();
            $table->string('hashname', 191)->nullable();
            $table->string('size', 191)->nullable();
            $table->string('dropbox_link', 191)->nullable();
            $table->string('external_link', 191)->nullable();
            $table->string('external_link_name', 191)->nullable();
            $table->timestamps();
            $table->foreign('faq_id')->references('id')->on('faqs')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->collation = 'utf8mb4_unicode_ci';
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faq_files');
    }
};
