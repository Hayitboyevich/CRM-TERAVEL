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
            $table->unsignedInteger('language_setting_id')->nullable()->after('id');
            $table->foreign('language_setting_id')->references('id')->on('language_settings')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::table('seo_details', function (Blueprint $table) {
            $table->unsignedInteger('language_setting_id')->nullable()->after('id');
            $table->foreign('language_setting_id')->references('id')->on('language_settings')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('footer_menu', function (Blueprint $table) {
            $table->dropForeign('footer_menu_language_setting_id_foreign');
            $table->dropColumn(['language_setting_id']);
        });

        Schema::table('seo_details', function (Blueprint $table) {
            $table->dropForeign('seo_details_language_setting_id_foreign');
            $table->dropColumn(['language_setting_id']);
        });
    }
};
