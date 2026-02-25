<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sponsorship_packages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->json('features')->nullable();
            $table->integer('tables_included')->default(0);
            $table->integer('guests_per_table')->default(10);
            $table->boolean('has_branding')->default(false);
            $table->boolean('has_speaking_slot')->default(false);
            $table->integer('max_available')->nullable();
            $table->integer('quantity_sold')->default(0);
            $table->enum('status', ['active', 'inactive', 'sold_out'])->default('active');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sponsorship_packages');
    }
};
