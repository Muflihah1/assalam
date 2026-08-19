<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'user_id',
        'total_price',
        'dp_amount',
        'shipping_cost',
        'remaining_payment',
        'payment_method',
        'payment_status',
        'production_status',
        'current_stage',
        'recipient_name',
        'recipient_phone',
        'shipping_address',
        'dp_receipt_proof',
        'final_receipt_proof',
        'admin_notes',
        'customer_notes',
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
        'dp_amount' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'remaining_payment' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function customDesign()
    {
        return $this->hasOne(CustomDesign::class, 'order_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    public function progresses()
    {
        return $this->hasMany(OrderProgress::class, 'order_id')->orderBy('step_number', 'asc');
    }
}
