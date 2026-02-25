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
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->string('stripe_session_id')->nullable();
            $table->string('stripe_payment_intent_id')->nullable();
            $table->string('stripe_customer_id')->nullable();
            $table->string('buyer_name');
            $table->string('buyer_email');
            $table->string('buyer_phone')->nullable();
            $table->text('billing_address')->nullable();
            $table->decimal('subtotal', 10, 2);
            $table->decimal('vat_amount', 10, 2)->default(0);
            $table->decimal('vat_rate', 5, 2)->default(20.00);
            $table->decimal('transaction_fee', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2);
            $table->string('currency', 3)->default('GBP');
            $table->enum('payment_status', ['pending', 'processing', 'completed', 'failed', 'cancelled'])->default('pending');
            $table->enum('refund_status', ['none', 'partial', 'full'])->default('none');
            $table->decimal('refunded_amount', 10, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['event_id', 'payment_status']);
            $table->index('buyer_email');
            $table->index('stripe_session_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
