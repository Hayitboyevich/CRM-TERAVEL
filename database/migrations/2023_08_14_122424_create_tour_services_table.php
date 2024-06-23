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
        Schema::create('tour_services', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('type_id');
            $table->double('price');
            $table->double('net_price');
            $table->unsignedBigInteger('currency_id');
            $table->unsignedBigInteger('net_currency_id');

            $table->double('exchange_rate');
            $table->double('net_exchange_rate');

            $table->unsignedBigInteger('adults_count')->nullable();
            $table->unsignedBigInteger('children_count')->nullable();

            $table->unsignedBigInteger('partner_id')->nullable();
            $table->unsignedBigInteger('country_id')->nullable();
            $table->unsignedBigInteger('region_id')->nullable();
            $table->unsignedBigInteger('schema_id')->nullable();
            $table->unsignedBigInteger('meal_id')->nullable();
            $table->unsignedBigInteger('room_type_id')->nullable();
            $table->unsignedBigInteger('hotel_id')->nullable();
            $table->date('date_from')->nullable();
            $table->date('date_to')->nullable();
            $table->unsignedInteger('travellers_count')->nullable();
            $table->unsignedBigInteger('flight_id')->nullable();
            $table->unsignedBigInteger('visa_id')->nullable();
            $table->unsignedBigInteger('bus_id')->nullable();
            $table->unsignedBigInteger('train_id')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tour_services');
    }
};
