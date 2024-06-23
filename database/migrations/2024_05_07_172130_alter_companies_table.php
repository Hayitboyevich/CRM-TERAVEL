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
//        Schema::table('companies', function (Blueprint $table) {
//            $table->unsignedInteger('week_start')->default(1)->notNull()->after('time_format');
//            $table->string('stripe_id', 191)->nullable()->after('updated_at');
//            $table->string('card_brand', 191)->nullable()->after('stripe_id');
//            $table->string('card_last_four', 191)->nullable()->after('card_brand');
//            $table->timestamp('trial_ends_at')->nullable()->after('card_last_four');
//            $table->date('licence_expire_on')->nullable()->after('trial_ends_at');
//            $table->tinyInteger('show_update_popup')->default(1)->notNull()->after('default_task_status');
//
//            // Foreign Key Constraints
//            $table->foreign('package_id')->references('id')->on('packages')->onUpdate('cascade')->onDelete('set null');
//            $table->foreign('last_updated_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('set null');
//        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
