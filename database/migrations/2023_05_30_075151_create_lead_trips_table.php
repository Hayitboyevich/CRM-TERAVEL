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
        Schema::create('lead_trips', function (Blueprint $table) {
            $table->id();
            $table->integer('lead_id');
            $table->string('country');
            $table->string('budget');
            $table->integer('members_count');
            $table->string('trip_service');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lead_trips');
    }
};
