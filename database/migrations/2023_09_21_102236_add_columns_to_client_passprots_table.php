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
        Schema::table('client_passports', function (Blueprint $table) {
            $table->string('departure_time');
            $table->string('arrival_time');
            $table->integer('nights');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('client_passports', function (Blueprint $table) {
            $table->dropColumn('departure_time');
            $table->dropColumn('arrival_time');
            $table->dropColumn('nights');
        });
    }
};
