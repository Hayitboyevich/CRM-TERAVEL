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
        Schema::create('integration_towns', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('easybooking_id')->nullable();
            $table->string('prestige_id')->nullable();
            $table->string('kompastour_id')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('integration_towns');
    }
};
