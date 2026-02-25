<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('scheduled_job_logs', function (Blueprint $table) {
            $table->id();
            $table->string('job_name');
            $table->string('job_class')->nullable();
            $table->enum('status', ['success', 'warning', 'failed'])->default('success');
            $table->decimal('execution_time', 10, 2)->nullable();
            $table->text('output')->nullable();
            $table->text('error')->nullable();
            $table->timestamp('started_at');
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('next_run_at')->nullable();
            $table->timestamps();

            $table->index(['job_name', 'status']);
            $table->index('started_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scheduled_job_logs');
    }
};
