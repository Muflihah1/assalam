<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudioSetting extends Model
{
    use HasFactory;

    // Menentukan nama tabel di database
    protected $table = 'studio_settings';

    // Kolom yang boleh diisi data dari form
    protected $fillable = ['key', 'value'];
}