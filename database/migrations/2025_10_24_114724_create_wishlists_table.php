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
        Schema::create('wishlists', function (Blueprint $table) {
            // Foreign Keys
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('cascade')
                  ->index(); // เพิ่ม index 

            $table->foreignId('product_id')
                  ->references('product_id')
                  ->on('products')
                  ->onDelete('cascade')
                  ->index(); // เพิ่ม index 

            // วันที่เพิ่มใน wishlist
            $table->date('wishlist_date')->index(); // ✅ ใช้บ่อย (เช่น เรียงตามเวลา)

            // Composite Primary Key
            $table->primary(['user_id', 'product_id']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wishlists');
    }
};
