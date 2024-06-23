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
        Schema::create('stripe_setting', function (Blueprint $table) {
            $table->id();
            $table->string('api_key', 191)->nullable();
            $table->string('api_secret', 191)->nullable();
            $table->string('webhook_key', 191)->nullable();
            $table->string('paypal_client_id', 191)->nullable();
            $table->string('paypal_secret', 191)->nullable();
            $table->enum('paypal_status', ['active', 'inactive'])->default('inactive')->nullable(false);
            $table->enum('stripe_status', ['active', 'inactive'])->default('inactive')->nullable(false);
            $table->string('razorpay_key', 191)->nullable();
            $table->string('razorpay_secret', 191)->nullable();
            $table->string('razorpay_webhook_secret', 191)->nullable();
            $table->enum('razorpay_status', ['active', 'deactive'])->default('deactive')->nullable(false);
            $table->timestamps();
            $table->enum('paypal_mode', ['sandbox', 'live'])->nullable(false);
            $table->string('paystack_client_id', 191)->nullable();
            $table->string('paystack_secret', 191)->nullable();
            $table->enum('paystack_status', ['active', 'inactive'])->default('inactive')->nullable();
            $table->string('paystack_merchant_email', 191)->nullable();
            $table->string('paystack_payment_url', 191)->default('https://api.paystack.co')->nullable();
            $table->string('mollie_api_key', 191)->nullable(false);
            $table->enum('mollie_status', ['active', 'inactive'])->default('inactive')->nullable(false);
            $table->string('authorize_api_login_id', 191)->nullable();
            $table->string('authorize_transaction_key', 191)->nullable();
            $table->string('authorize_signature_key', 191)->nullable();
            $table->string('authorize_environment', 191)->nullable();
            $table->enum('authorize_status', ['active', 'inactive'])->default('inactive')->nullable(false);
            $table->string('payfast_key', 191)->nullable();
            $table->string('payfast_secret', 191)->nullable();
            $table->enum('payfast_status', ['active', 'inactive'])->default('inactive')->nullable(false);
            $table->string('payfast_salt_passphrase', 191)->nullable();
            $table->enum('payfast_mode', ['sandbox', 'live'])->default('sandbox')->nullable(false);
            $table->collation = 'utf8mb4_unicode_ci';
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stripe_setting');
    }
};
