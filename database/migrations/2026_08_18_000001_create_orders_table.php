<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->decimal('total_price', 12, 2)->default(0);
            $table->decimal('dp_amount', 12, 2)->default(0);
            $table->decimal('shipping_cost', 12, 2)->default(0);
            $table->decimal('remaining_payment', 12, 2)->default(0);
            $table->string('payment_method')->default('qris');
            $table->string('payment_status')->default('Menunggu Pembayaran DP'); // Menunggu Pembayaran DP, DP Terverifikasi, Lunas, Ditolak
            $table->string('production_status')->default('Menunggu Konfirmasi'); // Menunggu Konfirmasi, Antrean Produksi, Dalam Pengerjaan, Siap Kirim, Selesai
            $table->string('current_stage')->default('Konfirmasi Pesanan');
            $table->string('recipient_name')->nullable();
            $table->string('recipient_phone')->nullable();
            $table->text('shipping_address')->nullable();
            $table->string('dp_receipt_proof')->nullable();
            $table->string('final_receipt_proof')->nullable();
            $table->text('admin_notes')->nullable();
            $table->text('customer_notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
