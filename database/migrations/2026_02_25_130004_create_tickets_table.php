<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('ticket_type_id')->constrained()->onDelete('cascade');
            $table->uuid('uuid')->unique();
            $table->string('ticket_number')->unique();
            $table->string('attendee_name')->nullable();
            $table->string('attendee_email')->nullable();
            $table->string('qr_code_path')->nullable();
            $table->boolean('is_checked_in')->default(false);
            $table->timestamp('checked_in_at')->nullable();
            $table->string('checked_in_by')->nullable();
            $table->string('check_in_method')->nullable();
            $table->enum('status', ['valid', 'used', 'cancelled', 'transferred'])->default('valid');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['order_id', 'status']);
            $table->index('is_checked_in');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
