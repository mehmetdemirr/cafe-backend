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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();       // Başlık (nullable)
            $table->string('subtitle')->nullable();    // Alt başlık (nullable)
            $table->text('description')->nullable();   // Açıklama (nullable)
            $table->dateTime('start_date');            // Başlangıç tarihi
            $table->dateTime('end_date')->nullable();  // Bitiş tarihi (nullable)
            $table->unsignedBigInteger('business_id'); // İşletme ID
            $table->integer('quota')->nullable();      // Katılım kontenjanı (nullable)
            $table->boolean('is_paid')->default(false); // Ücretli mi? (Varsayılan false)
            $table->integer('views')->default(0);      // Görüntülenme sayısı (Varsayılan 0)
            $table->unsignedTinyInteger('category');   // Kategori (1,2,3,4 gibi int)
            $table->string('image_url')->nullable();   // Görsel bağlantısı (nullable)
            $table->string('location')->nullable();    // İşletme dışı etkinlikler için yer bilgisi (nullable)
            $table->decimal('price', 8, 2)->nullable(); // Ücret bilgisi (nullable)
            $table->dateTime('registration_deadline')->nullable(); // Kayıt son tarihi (nullable)
            $table->boolean('is_offsite')->default(false); // İşletme dışında mı yapılacak? (Varsayılan false)
            
            $table->foreign('business_id')->references('id')->on('businesses')->onDelete('cascade'); // İşletme ilişkisi
            $table->timestamps();  // created_at ve updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
