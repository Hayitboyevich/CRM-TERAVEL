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
//        Schema::create('offline_plan_changes', function (Blueprint $table) {
//            $table->bigIncrements('id');
//            $table->unsignedInteger('company_id')->nullable(false);
//            $table->unsignedInteger('package_id')->nullable(false);
//            $table->string('package_type', 191)->nullable(false);
//            $table->unsignedBigInteger('invoice_id')->nullable(false);
//            $table->unsignedInteger('offline_method_id')->nullable(false);
//            $table->string('file_name', 191)->nullable();
//            $table->enum('status', ['verified', 'pending', 'rejected'])->default('pending')->nullable(false);
//            $table->mediumText('description')->nullable(false);
//            $table->timestamps();
//
//            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade')->onUpdate('cascade');
//            $table->foreign('invoice_id')->references('id')->on('offline_invoices')->onDelete('cascade')->onUpdate('cascade');
//            $table->foreign('offline_method_id')->references('id')->on('offline_payment_methods')->onDelete('cascade')->onUpdate('cascade');
//            $table->foreign('package_id')->references('id')->on('packages')->onDelete('cascade')->onUpdate('cascade');
//
//            $table->collation = 'utf8mb4_unicode_ci';
//        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offline_plan_changes');
    }
};
