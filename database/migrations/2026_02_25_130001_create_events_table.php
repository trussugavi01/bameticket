<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('category')->nullable();
            $table->string('image')->nullable();
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->string('venue_name')->nullable();
            $table->text('venue_address')->nullable();
            $table->decimal('venue_lat', 10, 8)->nullable();
            $table->decimal('venue_lng', 11, 8)->nullable();
            $table->string('dress_code')->nullable();
            $table->time('doors_open')->nullable();
            $table->time('dinner_time')->nullable();
            $table->enum('status', ['draft', 'published', 'selling', 'sold_out', 'ended', 'archived'])->default('draft');
            $table->boolean('is_featured')->default(false);
            $table->integer('total_capacity')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
