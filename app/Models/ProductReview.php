<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ProductReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'produk_id',
        'user_id',
        'order_id',
        'rating',
        'title',
        'comment',
        'photo',
        'is_verified_buyer',
        'status',
        'admin_note',
    ];

    protected $casts = [
        'rating' => 'integer',
        'is_verified_buyer' => 'boolean',
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function getUrlPhotoAttribute(): ?string
    {
        if (empty($this->photo)) {
            return null;
        }

        if (str_starts_with($this->photo, 'http://') || str_starts_with($this->photo, 'https://')) {
            return $this->photo;
        }

        return Storage::disk('public')->exists($this->photo)
            ? Storage::url($this->photo)
            : null;
    }

    public function getAuthorNameAttribute(): string
    {
        if ($this->user) {
            return $this->user->name ?: ($this->user->username ?? 'Pelanggan');
        }

        return 'Pelanggan';
    }

    public function getAuthorAvatarAttribute(): string
    {
        return $this->user?->profile_photo_url ?? 'https://api.dicebear.com/7.x/adventurer/svg?seed=' . urlencode($this->getAuthorNameAttribute());
    }

    public function scopeDisetujui($query)
    {
        return $query->where('status', 'Disetujui');
    }
}
