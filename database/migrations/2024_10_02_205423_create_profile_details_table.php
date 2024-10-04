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
        Schema::create('profile_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('phone_number')->nullable();
            $table->string('biography')->nullable();
            $table->string('country')->nullable();
            $table->string( 'city')->nullable();
            $table->string('district')->nullable();
            $table->boolean('is_premium')->default(false);
            $table->string('gender')->nullable();
            $table->string('profile_picture')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profile_details');
    }
};
