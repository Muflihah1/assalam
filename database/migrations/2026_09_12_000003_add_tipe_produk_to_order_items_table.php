<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->string('tipe_produk')->default('pre_order')->after('image'); // 'ready', 'pre_order'
        });
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn('tipe_produk');
        });
    }
};
