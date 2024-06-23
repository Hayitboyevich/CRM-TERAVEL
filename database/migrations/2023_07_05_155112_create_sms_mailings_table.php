<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sms_mailings', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id')->nullable(); // Foreign key for the user associated with the SMS
            $table->json('user_id'); // Foreign key for the user associated with the SMS
            $table->text('message');
            $table->string('status')->default('pending'); // Status of the SMS (e.g., pending, sent, delivered)
            $table->timestamp('delivery_date')->nullable(); // Date and time when the SMS should be delivered
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sms_mailings');
    }
};
