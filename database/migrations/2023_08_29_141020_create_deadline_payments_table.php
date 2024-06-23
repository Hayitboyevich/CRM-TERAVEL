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
        Schema::create('deadline_payments', function (Blueprint $table) {
            $table->id();
            $table->integer('application_id');
            $table->integer('percent')->nullable();
            $table->timestamp('deadline')->nullable();
            $table->double('amount')->nullable();
            $table->string('type');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deadline_payments');
    }
};
