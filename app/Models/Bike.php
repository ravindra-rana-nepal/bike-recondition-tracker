<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bike extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'vin', 'make', 'model', 'year', 'color', 'engine_no', 'registration_no',
        'purchase_price', 'estimated_selling_price', 'sold_price', 'status',
        'damage_details', 'reconditioning_notes', 'additional_features', 'notes',
        'seller_id', 'buyer_id', 'purchase_date', 'sale_date',
        'reconditioning_start_date', 'reconditioning_end_date'
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'sale_date' => 'date',
        'purchase_price' => 'decimal:2',
        'estimated_selling_price' => 'decimal:2',
        'sold_price' => 'decimal:2',
    ];

    // Relationships
    public function seller()
    {
        return $this->belongsTo(Customer::class, 'seller_id');
    }

    public function buyer()
    {
        return $this->belongsTo(Customer::class, 'buyer_id');
    }

    public function reconditioningLogs()
    {
        return $this->hasMany(ReconditioningLog::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    // Accessors
    public function getFullNameAttribute()
    {
        return "{$this->make} {$this->model} ({$this->year})";
    }

    public function getTotalReconditioningCostAttribute()
    {
        return $this->reconditioningLogs()->sum('cost');
    }

    public function getTotalInvestmentAttribute()
    {
        return $this->purchase_price + $this->total_reconditioning_cost;
    }

    public function getProfitLossAttribute()
    {
        if ($this->status === 'sold' && $this->sold_price) {
            return $this->sold_price - $this->total_investment;
        }
        return 0;
    }
}