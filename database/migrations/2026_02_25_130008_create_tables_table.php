<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->foreignId('sponsor_id')->nullable()->constrained()->onDelete('set null');
            $table->string('table_number');
            $table->string('name')->nullable();
            $table->integer('capacity')->default(10);
            $table->enum('status', ['available', 'reserved', 'sold_out'])->default('available');
            $table->decimal('position_x', 8, 2)->nullable();
            $table->decimal('position_y', 8, 2)->nullable();
            $table->timestamps();

            $table->unique(['event_id', 'table_number']);
            $table->index(['event_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tables');
    }
};
