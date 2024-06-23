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
        Schema::create('airtickets', function (Blueprint $table) {
            $table->id();
            $table->string('from')->nullable();
            $table->integer('from_code')->nullable();
            $table->integer('from_terminal')->nullable();
            $table->timestamp('flightDate')->nullable();

            $table->string('to')->nullable();
            $table->integer('to_code')->nullable();
            $table->integer('to_terminal')->nullable();
            $table->timestamp('landingDate')->nullable();
            $table->string('type')->nullable();
            $table->string('race_number')->nullable();
            $table->string('class')->nullable();


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('airtickets');
    }
};
