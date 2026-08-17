<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'type', 'email', 'phone', 'cnic', 'address', 'notes'
    ];

    // Relationships
    public function soldBikes()
    {
        return $this->hasMany(Bike::class, 'seller_id');
    }

    public function purchasedBikes()
    {
        return $this->hasMany(Bike::class, 'buyer_id');
    }

    // Scopes
    public function scopeSellers($query)
    {
        return $query->where('type', 'seller');
    }

    public function scopeBuyers($query)
    {
        return $query->where('type', 'buyer');
    }

    // Accessors
    public function getDisplayNameAttribute()
    {
        return "{$this->name} ({$this->phone})";
    }
}