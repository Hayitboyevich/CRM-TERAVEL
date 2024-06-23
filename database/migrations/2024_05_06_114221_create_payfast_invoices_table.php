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
//        Schema::create('payfast_invoices', function (Blueprint $table) {
//            $table->id();
//            $table->unsignedInteger('company_id')->nullable();
//            $table->unsignedInteger('package_id')->nullable();
//            $table->string('m_payment_id')->nullable();
//            $table->string('pf_payment_id')->nullable();
//            $table->string('payfast_plan')->nullable();
//            $table->string('amount')->nullable();
//            $table->date('pay_date')->nullable();
//            $table->date('next_pay_date')->nullable();
//            $table->string('signature')->nullable();
//            $table->string('token')->nullable();
//            $table->string('status')->nullable();
//            $table->timestamps();
//
//            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade')->onUpdate('cascade');
//            $table->foreign('package_id')->references('id')->on('packages')->onDelete('cascade')->onUpdate('cascade');
//        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payfast_invoices');
    }
};
