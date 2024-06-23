<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('lead_interests', function (Blueprint $table) {
            if (Schema::hasColumn('lead_interests', 'type')) {
                $table->dropColumn('type');
            }
//            $table->id();
            $table->unsignedInteger('lead_id');
            $table->unsignedInteger('country_id')->nullable();
            $table->integer('adults')->nullable();
            $table->integer('children')->nullable();
            $table->integer('baby')->nullable();
            $table->date('desired_date_from')->nullable();
            $table->date('desired_date_to')->nullable();
            $table->integer('count_days_from')->nullable();
            $table->integer('count_days_to')->nullable();
            $table->string('accommodation_type')->nullable();
            $table->enum('meal_plan', ['RO', 'BB', 'HB', 'FB', 'AI', 'UAI'])->nullable();
            $table->string('price')->nullable();
            $table->string('note')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
