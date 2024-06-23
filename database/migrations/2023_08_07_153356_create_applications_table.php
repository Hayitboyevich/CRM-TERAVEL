<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('client_id');
            $table->unsignedInteger('company_id');
            $table->unsignedInteger('source_id')->nullable();
            $table->unsignedInteger('status_id');
            $table->unsignedBigInteger('agent_id')->nullable();
            $table->unsignedBigInteger('partner_id')->nullable();
            $table->unsignedInteger('added_by')->nullable();
            $table->integer('order_number')->nullable();
            $table->unsignedInteger('country_id')->nullable();
            $table->unsignedInteger('type_id')->nullable();

            $table->string('company_name');

            $table->timestamps();

            $table->foreign('client_id') // Define the foreign key
            ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->foreign('added_by') // Define the foreign key
            ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->foreign('agent_id') // Define the foreign key
            ->references('id')
                ->on('lead_agents')
                ->onDelete('cascade');
            $table->foreign('partner_id') // Define the foreign key
            ->references('id')
                ->on('integration_partners')
                ->onDelete('cascade');
            $table->foreign('source_id') // Define the foreign key
            ->references('id')
                ->on('lead_sources')
                ->onDelete('cascade');
            $table->foreign('company_id') // Define the foreign key
            ->references('id')
                ->on('companies')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
