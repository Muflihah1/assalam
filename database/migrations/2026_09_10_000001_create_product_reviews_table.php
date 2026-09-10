<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produk_id')->constrained('produks')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('order_id')->nullable()->constrained('orders')->onDelete('set null');
            $table->unsignedTinyInteger('rating'); // 1..5
            $table->string('title', 150)->nullable();
            $table->text('comment');
            $table->string('photo', 255)->nullable(); // foto ulasan produk
            $table->boolean('is_verified_buyer')->default(false);
            $table->string('status', 30)->default('Menunggu Persetujuan'); // Menunggu Persetujuan | Disetujui | Ditolak
            $table->text('admin_note')->nullable();
            $table->timestamps();

            $table->index(['produk_id', 'status']);
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_reviews');
    }
};
