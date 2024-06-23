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
        Schema::create('integrations', function (Blueprint $table) {
            $table->id();
            $table->integer('from_city_id');
            $table->string('from_city_name');

            $table->integer('to_country_id');
            $table->string('to_country_name');

            $table->integer('to_city_id');
            $table->string('to_city_name');

            $table->string('checkin_begin');
            $table->string('checkin_end');
            $table->integer('user_id');

            $table->integer('category_id');
            $table->string('category_name');

            $table->integer('hotel_id');
            $table->integer('hotel_name');

            $table->double('budget');

            $table->integer('meal_id')->nullable();

            $table->string('cost_min')->nullable();
            $table->string('cost_max')->nullable();

            $table->string('tour_id');
            $table->string('tour_name');

            $table->string('program_type_id')->nullable();

            $table->string('nights_count_from')->nullable();
            $table->string('nights_count_to')->nullable();
            $table->string('currency_id')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('integrations');
    }
};
