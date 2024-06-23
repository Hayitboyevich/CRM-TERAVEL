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
//        Schema::create('client_passports', function (Blueprint $table) {
//            $table->id();
//            $table->integer('client_id');
//            $table->enum('passport_type', ['passport', 'touristic']);
//            $table->string('passport_serial_number')->nullable();
//            $table->string('first_name')->nullable();
//            $table->string('last_name')->nullable();
//            $table->date('date_of_birth')->nullable();
//            $table->date('date_of_expiry')->nullable();
//            $table->string('nationality')->nullable();
//            $table->string('gender')->nullable();
//            $table->string('place_of_birth')->nullable();
//            $table->date('given_date')->nullable();
//            $table->string('given_by')->nullable();
//            $table->string('given_department')->nullable();
//            $table->string('personal_number')->nullable();
//
//            $table->integer('stir')->nullable();
//            $table->integer('residence_id')->nullable();
//            $table->integer('living_country_id')->nullable();
//
//            $table->timestamps();
//        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_passports');
    }
};
