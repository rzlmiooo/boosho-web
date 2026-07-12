<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['user_id', 'total_price', 'status', 'payment_code', 'address', 'latitude', 'longitude', 'shipping_resi'];

    // Relasi ke User (Pembeli)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke detail item pesanan
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
