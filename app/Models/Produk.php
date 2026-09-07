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

    protected $appends = ['foto_url', 'default_dimensions'];

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

    /**
     * Accessor untuk dimensi standar produk berdasarkan deskripsi atau jenis produk
     * Mengembalikan array ['length' => ..., 'width' => ..., 'height' => ...]
     */
    public function getDefaultDimensionsAttribute(): array
    {
        $desc = $this->deskripsi ?? '';
        $name = strtolower($this->nama ?? '');

        // 1. Cek pola 3 dimensi: misal P150×L60×T95 atau P250xL130xT4
        if (preg_match('/[Pp]?\s*(\d{1,3})\s*[×x*]\s*[Ll]?\s*(\d{1,3})\s*[×x*]\s*[Tt]?\s*(\d{1,3})/ui', $desc, $matches)) {
            return [
                'length' => (int) $matches[1],
                'width' => (int) $matches[2],
                'height' => (int) $matches[3],
            ];
        }

        // 2. Cek pola 2 dimensi + tebal: misal 250×130 cm, tebal 4 cm (Pintu Tarung)
        if (preg_match('/(\d{2,3})\s*[×x*]\s*(\d{2,3})\s*cm(?:.*tebal\s*(\d{1,2})\s*cm)?/ui', $desc, $matches)) {
            $dim1 = (int) $matches[1];
            $dim2 = (int) $matches[2];
            $tebal = !empty($matches[3]) ? (int) $matches[3] : 10;

            if (str_contains($name, 'pintu')) {
                return [
                    'length' => min($dim1, $dim2),
                    'width' => max(10, $tebal),
                    'height' => max($dim1, $dim2),
                ];
            }

            if (str_contains($name, 'blawong') || str_contains($name, 'logo') || str_contains($name, 'ukiran') || str_contains($name, 'panel')) {
                return [
                    'length' => max($dim1, $dim2),
                    'width' => min($dim1, $dim2),
                    'height' => max(4, $tebal),
                ];
            }

            return [
                'length' => max($dim1, $dim2),
                'width' => min($dim1, $dim2),
                'height' => 75,
            ];
        }

        // 3. Fallback cerdas berdasarkan nama produk mebel
        if (str_contains($name, 'lemari')) {
            return ['length' => 120, 'width' => 60, 'height' => 200];
        } elseif (str_contains($name, 'podium') || str_contains($name, 'mimbar')) {
            return ['length' => 70, 'width' => 60, 'height' => 125];
        } elseif (str_contains($name, 'pendopo') || str_contains($name, 'gazebo')) {
            return ['length' => 300, 'width' => 300, 'height' => 280];
        } elseif (str_contains($name, 'pintu')) {
            return ['length' => 130, 'width' => 10, 'height' => 240];
        } elseif (str_contains($name, 'blawong') || str_contains($name, 'ukiran') || str_contains($name, 'logo')) {
            return ['length' => 65, 'width' => 45, 'height' => 4];
        } elseif (str_contains($name, 'kursi') || str_contains($name, 'sofa')) {
            return ['length' => 150, 'width' => 60, 'height' => 95];
        } elseif (str_contains($name, 'meja')) {
            return ['length' => 120, 'width' => 60, 'height' => 75];
        }

        return ['length' => 180, 'width' => 80, 'height' => 75];
    }
}