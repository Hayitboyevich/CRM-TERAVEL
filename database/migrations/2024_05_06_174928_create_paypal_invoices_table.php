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
        Schema::create('paypal_invoices', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('company_id')->nullable();
            $table->unsignedInteger('currency_id')->nullable();
            $table->unsignedInteger('package_id')->nullable();
            $table->double('sub_total')->nullable();
            $table->double('total')->nullable();
            $table->string('transaction_id', 191)->nullable();
            $table->string('remarks', 191)->nullable();
            $table->string('billing_frequency', 191)->nullable();
            $table->integer('billing_interval')->nullable();
            $table->dateTime('paid_on')->nullable();
            $table->dateTime('next_pay_date')->nullable();
            $table->enum('recurring', ['yes', 'no'])->default('no')->nullable();
            $table->enum('status', ['paid', 'unpaid', 'pending'])->default('pending')->nullable();
            $table->string('plan_id', 191)->nullable();
            $table->string('event_id', 191)->nullable();
            $table->dateTime('end_on')->nullable();
            $table->timestamps();

            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('package_id')->references('id')->on('packages')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('currency_id')->references('id')->on('currencies');

            $table->collation = 'utf8mb4_unicode_ci';
        });

        Schema::table('paypal_invoices', function (Blueprint $table) {
            $table->index('currency_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paypal_invoices');
    }
};
