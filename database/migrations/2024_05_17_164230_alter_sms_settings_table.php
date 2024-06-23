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
        //check if sms_settings table exists
        if (Schema::hasTable('sms_settings')) {
            Schema::drop('sms_settings');
        }

        Schema::create('sms_settings', function (Blueprint $table) {
            $table->id();
            $table->string('url');
            $table->string('username');
            $table->string('password');
            $table->unsignedInteger('company_id');
            $table->timestamps();

            $table->unique('company_id');

            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
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
