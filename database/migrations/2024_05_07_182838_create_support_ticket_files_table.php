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
        Schema::create('support_ticket_files', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id');
            $table->unsignedBigInteger('support_ticket_reply_id');
            $table->string('filename', 191);
            $table->text('description')->nullable();
            $table->string('google_url', 191)->nullable();
            $table->string('hashname', 191)->nullable();
            $table->string('size', 191)->nullable();
            $table->string('dropbox_link', 191)->nullable();
            $table->string('external_link', 191)->nullable();
            $table->string('external_link_name', 191)->nullable();
            $table->timestamps();

            // Foreign Key Constraints
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('support_ticket_reply_id')->references('id')->on('support_ticket_replies')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('support_ticket_files');
    }
};
