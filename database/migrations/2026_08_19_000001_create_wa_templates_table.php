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
        Schema::create('wa_templates', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // e.g. 'order_created', 'dp_verified', 'progress_updated', 'payment_completed', 'order_finished'
            $table->string('name'); // e.g. 'Pemberitahuan Progres Produksi'
            $table->string('event_trigger'); // e.g. 'Saat Tahap Produksi Diperbarui'
            $table->text('content');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wa_templates');
    }
};
