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
        Schema::create('menu_items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('price', 8, 2);
            $table->text('description')->nullable();
            $table->foreignId('menu_category_id')->constrained('menu_categories')->onDelete('cascade');
            $table->foreignId('business_id')->constrained('businesses')->onDelete('cascade'); // İşletme ID'si
            $table->boolean('is_available')->default(true); // Ürün durumu
            $table->text('additional_info')->nullable(); // Ek bilgi
            $table->integer('calories')->nullable(); // Kalori bilgisi
            $table->string('image_url')->nullable(); // image
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_items');
    }
};
