<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Ensuring that the database can handle the change by checking the existing content
        Schema::table('lead_interests', function (Blueprint $table) {
            // Convert any existing non-integer values to `null` or a default value
            DB::statement('UPDATE lead_interests SET price = NULL WHERE price NOT REGEXP \'^[0-9]+$\'');

            // Change the column to integer
            $table->integer('price')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lead_interests', function (Blueprint $table) {
            //
        });
    }
};
