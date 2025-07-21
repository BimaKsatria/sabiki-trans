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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rental_id')->constrained('rentals')->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->timestamp('payment_date')->useCurrent();
            $table->string('payment_method');
            $table->enum('status', ['paid', 'unpaid', 'failed'])->default('unpaid');
            // ID unik dari Midtrans
            $table->string('transaction_id')->nullable();
            // ID transaksi/order buatan sendiri yang dikirim ke Midtrans (harus unik)
            $table->string('order_id')->unique();
            // Snap token untuk Snap Popup
            $table->text('snap_token')->nullable();
            // Jika menggunakan redirect, URL untuk diarahkan
            $table->text('redirect_url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
