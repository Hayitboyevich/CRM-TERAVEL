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
        Schema::create('front_menu_buttons', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('language_setting_id')->nullable();
            $table->string('home', 20)->nullable()->default('home');
            $table->string('feature', 20)->nullable()->default('feature');
            $table->string('price', 20)->nullable()->default('price');
            $table->string('contact', 20)->nullable()->default('contact');
            $table->string('get_start', 20)->nullable()->default('get_start');
            $table->string('login', 20)->nullable()->default('login');
            $table->string('contact_submit', 20)->nullable()->default('contact_submit');
            $table->foreign('language_setting_id')->references('id')->on('language_settings')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });

        $frontMenu = new \App\FrontMenu();
        $frontMenu->home           = 'Home';
        $frontMenu->price          = 'Pricing';
        $frontMenu->contact        = 'Contact';
        $frontMenu->feature        = 'Features';
        $frontMenu->get_start      = 'Get Started';
        $frontMenu->login          = 'Login';
        $frontMenu->contact_submit = 'Submit Enquiry';
        $frontMenu->save();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('front_menu_buttons');
    }
};
