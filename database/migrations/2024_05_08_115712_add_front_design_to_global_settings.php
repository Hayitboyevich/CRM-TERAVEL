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
        if (!Schema::hasColumn('global_settings', 'front_design')) {
            Schema::table('global_settings', function (Blueprint $table) {
                $table->boolean('front_design')->default(0);
                $table->boolean('frontend_disable')->default(false);
                $table->string('setup_homepage')->default('default');
                $table->string('custom_homepage_url')->nullable();
                $table->boolean('login_ui');
                $table->text('s')->nullable();
                $table->boolean('enable_register')->default(1);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('global_settings', function (Blueprint $table) {
            //
        });
    }
};
