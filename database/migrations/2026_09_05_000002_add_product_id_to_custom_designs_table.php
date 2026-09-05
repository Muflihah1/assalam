<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('custom_designs', function (Blueprint $table) {
            $table->foreignId('product_id')->nullable()->after('order_id')->constrained('produks')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('custom_designs', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
            $table->dropColumn('product_id');
        });
    }
};
