<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('custom_designs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->string('category')->default('Sofa & Kursi Tamu Mewah');
            $table->integer('length_cm')->default(180);
            $table->integer('width_cm')->default(80);
            $table->integer('height_cm')->default(75);
            $table->string('wood_material')->default('Kayu Jati Perhutani (Grade A)');
            $table->string('color_name')->default('Amber Gold');
            $table->string('color_hex')->default('#d97706');
            $table->integer('tone_percent')->default(100);
            $table->string('sketch_image')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('custom_designs');
    }
};
