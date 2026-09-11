<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;



// ... use statements ...

/**
 * @property string $role
 * @property string $email
 * @property string $name
 */

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'whatsapp_number', 
        'email',
        'password',
        'alamat',
        'role',
        'profile_photo',
    ];

    /**
     * URL Foto Profil Pengguna (Dukungan foto unggahan atau fallback DiceBear)
     */
    public function getProfilePhotoUrlAttribute(): string
    {
        if ($this->profile_photo && \Illuminate\Support\Facades\Storage::disk('public')->exists($this->profile_photo)) {
            return \Illuminate\Support\Facades\Storage::url($this->profile_photo);
        }

        return 'https://api.dicebear.com/7.x/adventurer/svg?seed=' . urlencode($this->name ?? 'User');
    }

    /**
     * Hapus berkas foto profil dari storage
     */
    public function deleteProfilePhoto(): void
    {
        if ($this->profile_photo && \Illuminate\Support\Facades\Storage::disk('public')->exists($this->profile_photo)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($this->profile_photo);
        }
        $this->update(['profile_photo' => null]);
    }

    /**
     * Check if user is administrator
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Orders placed by user
     */
    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id');
    }

    /**
     * Password reset OTP tokens
     */
    public function passwordResetOtps()
    {
        return $this->hasMany(PasswordResetOtp::class);
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}

