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
//        Schema::create('offline_invoices', function (Blueprint $table) {
//            $table->id();
//            $table->unsignedInteger('company_id');
//            $table->unsignedInteger('package_id');
//            $table->string('package_type')->nullable();
//            $table->unsignedInteger('offline_method_id')->nullable();
//            $table->string('transaction_id')->nullable();
//            $table->decimal('amount', 12, 2);
//            $table->date('pay_date');
//            $table->date('next_pay_date')->nullable();
//            $table->enum('status', ['paid', 'unpaid', 'pending'])->default('pending');
//            $table->timestamps();
//
//            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade')->onUpdate('cascade');
//            $table->foreign('package_id')->references('id')->on('packages')->onDelete('cascade')->onUpdate('cascade');
//            $table->foreign('offline_method_id')->references('id')->on('offline_payment_methods')->onDelete('set null')->onUpdate('cascade');
//        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offline_invoices');
    }
};
