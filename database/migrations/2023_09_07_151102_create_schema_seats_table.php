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
        Schema::create('schema_seats', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('schema_id');
            $table->integer('row');
            $table->integer('cell');
            $table->integer('column_size');
            $table->integer('index');
            $table->integer('company_id');

            $table->timestamps();

            $table->foreign('schema_id')
                ->references('id')
                ->on('schemas')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schema_seats');
    }
};
