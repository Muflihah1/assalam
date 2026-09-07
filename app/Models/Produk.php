<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Produk extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'deskripsi',
        'harga',
        'foto',
    ];

    /**
     * Accessor untuk URL foto produk yang valid (baik path storage lokal maupun direct URL)
     */
    public function getFotoUrlAttribute(): ?string
    {
        if (empty($this->foto)) {
            return null;
        }

        if (str_starts_with($this->foto, 'http://') || str_starts_with($this->foto, 'https://')) {
            return $this->foto;
        }

        // Cek jika file tersimpan di storage public
        if (Storage::disk('public')->exists($this->foto)) {
            return Storage::url($this->foto);
        }

        // Fallback jika file berada langsung di folder public/ (misal "produk/...")
        if (file_exists(public_path($this->foto))) {
            return asset($this->foto);
        }

        return Storage::url($this->foto);
    }
}