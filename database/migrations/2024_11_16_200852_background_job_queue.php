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
        Schema::create('background_jobs_queue', function (Blueprint $table) {
            $table->id(); // Auto-incrementing ID
            $table->string('className'); // Class name (string)
            $table->smallInteger('priority'); // Priority (small integer)
            $table->json('parameters'); // JSON field to store various data
            $table->timestamps(); // Created_at and updated_at timestamps
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('background_jobs_queue');
    }
};
