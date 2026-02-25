<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('table_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('table_id')->constrained()->onDelete('cascade');
            $table->foreignId('ticket_id')->nullable()->constrained()->onDelete('set null');
            $table->string('attendee_name');
            $table->string('attendee_email')->nullable();
            $table->string('organization')->nullable();
            $table->string('dietary_type')->nullable();
            $table->text('dietary_notes')->nullable();
            $table->enum('dietary_status', ['pending', 'confirmed', 'kitchen_alerted'])->default('pending');
            $table->integer('seat_number')->nullable();
            $table->timestamps();

            $table->index(['table_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('table_assignments');
    }
};
