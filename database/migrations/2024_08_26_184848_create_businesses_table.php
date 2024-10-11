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
        Schema::create('businesses', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('slug')->unique();
            $table->string('address')->nullable();
            $table->string('qr_code')->nullable()->unique();   //giriş qr olacak
            $table->string('phone_number')->nullable();       // Telefon numarası
            $table->string('website_url')->nullable();        // Web sitesi URL'si
            $table->text('description')->nullable();          // İşletme açıklaması
            $table->decimal('location_latitude', 10, 8)->nullable();   // Konum (enlem)
            $table->decimal('location_longitude', 11, 8)->nullable();  // Konum (boylam)
            $table->string('image_url')->nullable();          // İşletme resmi URL'si
            $table->time('opening_time')->nullable();         // Açılış saati
            $table->time('closing_time')->nullable();         // Kapanış saati

            // user_id alanını foreign key ve unique olarak tanımlıyoruz
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('businesses');
    }
};
