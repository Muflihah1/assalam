<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_progresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->integer('step_number')->default(1);
            $table->string('stage_name'); // Konfirmasi Pesanan, Validasi Pembayaran, Pesanan Diterima, Menyiapkan Bahan, Perakitan, Penyelesaian, Pengiriman, Pesanan Selesai
            $table->string('status')->default('Pending'); // Pending, Sedang Berjalan, Selesai
            $table->json('media_files')->nullable(); // Array of image/video paths
            $table->text('notes')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_progresses');
    }
};
