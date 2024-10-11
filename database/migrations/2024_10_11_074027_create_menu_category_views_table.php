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
        Schema::create('menu_category_views', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_category_id')->constrained()->onDelete('cascade');
            $table->string('ip_address');
            $table->string('user_agent');
            $table->timestamp('viewed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_category_views');
    }
};
