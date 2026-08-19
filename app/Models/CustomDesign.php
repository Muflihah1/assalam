<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomDesign extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'category',
        'length_cm',
        'width_cm',
        'height_cm',
        'wood_material',
        'color_name',
        'color_hex',
        'tone_percent',
        'sketch_image',
        'notes',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
