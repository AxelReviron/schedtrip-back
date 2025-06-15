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
        Schema::create('trips', function (Blueprint $table) {
            $table->uuid('id')->primary()->unique();
            $table->string('label');
            $table->string('description')->nullable();
            $table->integer('distance')->nullable();
            $table->smallInteger('duration')->nullable();
            $table->boolean('is_public')->default(false);
            $table->json('geojson')->nullable();
            $table->foreignUuid('author_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trips');
    }
};
