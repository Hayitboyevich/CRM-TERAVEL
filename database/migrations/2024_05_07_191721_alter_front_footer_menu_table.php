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
        Schema::table('footer_menu', function (Blueprint $table) {
            $table->string('external_link')->nullable()->default(null);
            $table->enum('type', ['header', 'footer', 'both'])->nullable()->default('footer');
            $table->enum('status', ['active', 'inactive'])->nullable()->default('active');
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
