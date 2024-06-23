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
        Schema::create('passport_scan', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('company_id');
            $table->integer('number');
            $table->string('date', 7); // Format MM-YYYY
            $table->timestamps();

            // Add a unique constraint on company_id and date
            $table->unique(['company_id', 'date']);

            // Add foreign key constraint if needed
            $table->foreign('company_id')->references('id')->on('companies');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('passport_scan');
    }
};
