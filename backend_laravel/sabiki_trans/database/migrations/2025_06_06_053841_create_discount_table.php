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
        Schema::create('discount', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('code')->unique(); // Kode diskon, contoh: PROMO10
            $table->enum('type', ['percentage', 'fixed']); // Jenis diskon
            $table->decimal('value', 10, 2); // Nilai diskon: % atau angka tetap
            $table->decimal('max_discount', 10, 2)->nullable(); // Diskon maksimum (untuk percentage)
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('usage_limit')->nullable(); // Batas maksimal penggunaan
            $table->integer('used_count')->default(0); // Berapa kali sudah digunakan
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discount');
    }
};
