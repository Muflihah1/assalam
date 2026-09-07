<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value'
    ];

    /**
     * Ambil nilai pengaturan berdasarkan key
     */
    public static function get(string $key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    /**
     * Simpan atau perbarui nilai pengaturan
     */
    public static function set(string $key, $value): static
    {
        return static::updateOrCreate(['key' => $key], ['value' => $value]);
    }

    /**
     * Ambil URL foto/gambar QR Code DANA resmi
     */
    public static function getDanaQrUrl(): string
    {
        $qrPath = static::get('payment_dana_qr');
        if ($qrPath && \Illuminate\Support\Facades\Storage::disk('public')->exists($qrPath)) {
            return \Illuminate\Support\Facades\Storage::disk('public')->url($qrPath);
        }
        return asset('images/dana_qr_card.svg');
    }
}