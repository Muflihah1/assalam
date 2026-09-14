<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('produks', function (Blueprint $table) {
            $table->string('tipe_produk')->default('pre_order')->after('harga'); // 'ready', 'pre_order'
            $table->string('estimasi_po')->nullable()->after('tipe_produk'); // misal: '7-14 Hari Kerja'
        });
    }

    public function down(): void
    {
        Schema::table('produks', function (Blueprint $table) {
            $table->dropColumn(['tipe_produk', 'estimasi_po']);
        });
    }
};
