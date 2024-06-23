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
        Schema::create('lead_helpers', function (Blueprint $table) {
            $table->id();
            $table->string('field')->comment('turi');
            $table->timestamp('time')->nullable()->comment('jonatilgan vaqt');
            $table->string('from_id')->nullable()->comment('foydalanuvchi id');
            $table->string('recipient_id')->nullable()->comment('qabul qiluvchi id');
            $table->string('from_username')->nullable()->comment('foydalanuvchi username');
            $table->string('media_id')->nullable()->comment('media id');
            $table->string('media_product_type')->nullable()->comment('media turi');
            $table->string('text_id')->nullable()->comment('text id');
            $table->string('message_mid')->nullable()->comment('message mi');
            $table->string('text_parent_id')->nullable()->comment('text parent id');
            $table->text('text')->nullable()->comment('text');
            $table->text('company_token')->nullable()->comment('Kompaniya token');
            $table->tinyInteger('status')->default(0)->comment('status');
            $table->string('comment_id')->nullable()->comment('comment id');
            $table->string('action')->nullable()->comment('reaction action');
            $table->string('reaction')->nullable()->comment('reaction');
            $table->string('emoji')->nullable()->comment(' reaction emoji');
            $table->string('previous_owner_app_id')->nullable()->comment('previous_owner_app_id in messaging_handover');
            $table->string('new_owner_app_id')->nullable()->comment('new_owner_app_id in messaging_handover');
            $table->string('metadata')->nullable()->comment('metadata in messaging_handover');
            $table->string('title')->nullable()->comment('title in postback');
            $table->string('payload')->nullable()->comment('payload in postback');
            $table->string('ref')->nullable()->comment('ref in referral');
            $table->string('source')->nullable()->comment('source in referral');
            $table->string('type')->nullable()->comment('type in referral');
            $table->integer('impressions')->nullable()->comment('impressions in story_insights');
            $table->integer('reach')->nullable()->comment('reach in story_insights');
            $table->integer('taps_forward')->nullable()->comment('taps_forward in story_insights');
            $table->integer('taps_back')->nullable()->comment('taps_back in story_insights');
            $table->integer('exits')->nullable()->comment('exits in story_insights');
            $table->integer('replies')->nullable()->comment('replies in story_insights');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lead_helpers');
    }
};
