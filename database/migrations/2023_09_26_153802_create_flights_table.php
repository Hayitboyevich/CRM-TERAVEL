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
        Schema::create('flights', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('application_id');

            $table->string('from_location')->nullable();
            $table->integer('from_code')->nullable();
            $table->string('from_terminal')->nullable();
            $table->datetime('from_datetime')->nullable();

            $table->string('to_location')->nullable();
            $table->integer('to_code')->nullable();
            $table->string('to_terminal')->nullable();
            $table->datetime('to_datetime')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flights');
    }
};
