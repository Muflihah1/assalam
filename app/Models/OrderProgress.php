<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderProgress extends Model
{
    use HasFactory;

    protected $table = 'order_progresses';

    protected $fillable = [
        'order_id',
        'step_number',
        'stage_name',
        'status',
        'media_files',
        'notes',
        'completed_at',
    ];

    protected $casts = [
        'media_files' => 'array',
        'completed_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
