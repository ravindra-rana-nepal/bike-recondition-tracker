<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reconditioning_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bike_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->enum('work_type', [
                'engine_repair',
                'electrical',
                'body_painting',
                'suspension',
                'brakes',
                'tyres_wheels',
                'general_service',
                'other'
            ]);
            $table->text('description');
            $table->decimal('cost', 10, 2);
            $table->string('mechanic_name')->nullable();
            $table->string('parts_used')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reconditioning_logs');
    }
};