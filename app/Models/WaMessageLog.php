<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WaMessageLog extends Model
{
    use HasFactory;

    protected $table = 'wa_message_logs';

    protected $fillable = [
        'order_id',
        'recipient_name',
        'recipient_phone',
        'template_code',
        'message_body',
        'status',
        'response_payload',
        'retry_count',
        'last_retry_at',
    ];

    protected $casts = [
        'last_retry_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
