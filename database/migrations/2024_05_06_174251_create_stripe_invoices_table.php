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
        Schema::create('stripe_invoices', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('company_id')->nullable(false);
            $table->string('invoice_id', 191)->nullable(false);
            $table->unsignedInteger('package_id')->nullable(false);
            $table->string('transaction_id', 191)->nullable(false);
            $table->decimal('amount', 12, 2)->unsigned()->nullable(false);
            $table->date('pay_date')->nullable(false);
            $table->date('next_pay_date')->nullable();
            $table->timestamps();

            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('package_id')->references('id')->on('packages')->onDelete('cascade')->onUpdate('cascade');

            $table->collation = 'utf8mb4_unicode_ci';
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stripe_invoices');
    }
};
