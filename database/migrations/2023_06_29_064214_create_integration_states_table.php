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
        Schema::create('integration_states', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('easybooking_id')->nullable();
            $table->integer('prestige_id')->nullable();
            $table->integer('kompastour_id')->nullable();
            $table->unique('name');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('integration_states');
    }
};
