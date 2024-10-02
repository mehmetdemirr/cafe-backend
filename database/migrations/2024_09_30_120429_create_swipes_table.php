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
        Schema::create('swipes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Kullanıcıyı tanımlamak için
            $table->foreignId('swiped_user_id')->constrained('users')->onDelete('cascade'); // Kaydırılan kullanıcı
            $table->boolean('is_right'); // Sağa kaydırma işlemi (eşleşme isteği) mi?
            $table->boolean('is_matched')->default(false); // Eşleşme durumu
            $table->timestamps();

            // Kullanıcı ve kaydırılan kullanıcı için benzersiz kısıtlama
            $table->unique(['user_id', 'swiped_user_id'], 'unique_swipe');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('swipes');
    }
};
