<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bikes', function (Blueprint $table) {
            $table->id();
            $table->string('vin')->unique();
            $table->string('make');
            $table->string('model');
            $table->year('year');
            $table->string('color');
            $table->string('engine_no')->nullable();
            $table->string('registration_no')->nullable();
            
            // Pricing
            $table->decimal('purchase_price', 12, 2);
            $table->decimal('estimated_selling_price', 12, 2);
            $table->decimal('sold_price', 12, 2)->nullable();
            
            // Status
            $table->enum('status', [
                'in_stock', 
                'in_reconditioning', 
                'ready_for_sale', 
                'sold', 
                'scrapped'
            ])->default('in_stock');
            
            // Details
            $table->text('damage_details')->nullable();
            $table->text('reconditioning_notes')->nullable();
            $table->text('additional_features')->nullable();
            $table->text('notes')->nullable();
            
            // Relationships - IMPORTANT: These reference customers table
            $table->foreignId('seller_id')->constrained('customers')->onDelete('cascade');
            $table->foreignId('buyer_id')->nullable()->constrained('customers')->onDelete('set null');
            
            // Dates
            $table->date('purchase_date');
            $table->date('sale_date')->nullable();
            $table->date('reconditioning_start_date')->nullable();
            $table->date('reconditioning_end_date')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bikes');
    }
};