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
        Schema::create('tour_packages', function (Blueprint $table) {
            $table->id();
            $table->string('type_id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('company_mobile')->nullable();

            $table->date('date_from')->nullable();
            $table->date('date_to')->nullable();

            $table->unsignedBigInteger('country_id')->nullable();
            $table->unsignedBigInteger('region_id')->nullable();
            $table->unsignedBigInteger('hotel_id')->nullable();
            $table->unsignedBigInteger('room_type_id')->nullable();
            $table->unsignedBigInteger('meal_id')->nullable();

            $table->double('price');
            $table->double('net_price');
            $table->unsignedBigInteger('currency_id');
            $table->unsignedBigInteger('net_currency_id');

            $table->double('exchange_rate');
            $table->double('net_exchange_rate');

            $table->integer('quantity');
            $table->integer('adults_count');
            $table->integer('children_count');
            $table->integer('infants_count');
            $table->integer('company_id');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tour_packages');
    }
};
