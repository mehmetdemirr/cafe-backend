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
        Schema::create('business_ratings', function (Blueprint $table) {
            $table->id();
            $table->integer('rating');
            $table->text('comment')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade')->unique();
            $table->foreignId('business_id')->constrained()->onDelete('cascade')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('business_ratings');
    }
};
