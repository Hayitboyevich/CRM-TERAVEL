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
        if (!Schema::hasColumn('global_settings', 'active_theme')) {
            Schema::table('global_settings', function (Blueprint $table) {
                $table->enum('active_theme', ['default', 'custom'])->default('default');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
//        Schema::table('global_settings', function (Blueprint $table) {
//            //
//        });
    }
};
