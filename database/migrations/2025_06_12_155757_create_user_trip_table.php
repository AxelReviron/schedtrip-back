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
        Schema::create('user_trip', function (Blueprint $table) {
            $table->foreignUuid('user_id')->constrained('users');
            $table->foreignUuid('trip_id')->constrained('trips');
            $table->enum('permission', ['view', 'update'])->default('view');
            $table->timestamps();

            $table->primary(['user_id', 'trip_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_trip');
    }
};
