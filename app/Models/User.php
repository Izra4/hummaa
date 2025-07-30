<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'nama_depan',
        'nama_belakang',
        'email',
        'no_whatsapp',
        'tanggal_lahir',
        'jenis_kelamin',
        'foto_profil',
        'password',
        'provider',
        'provider_id',
        'avatar',
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'provider_id',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'tanggal_lahir' => 'date',
        'password' => 'hashed',
    ];

    /**
     * Get the user's full name.
     */
    public function getFullNameAttribute(): string
    {
        // Fallback to 'name' field if nama_depan/nama_belakang not set
        if ($this->nama_depan || $this->nama_belakang) {
            return trim($this->nama_depan . ' ' . $this->nama_belakang);
        }
        
        return $this->name ?? '';
    }

    /**
     * Get the user's profile picture URL.
     */
    public function getProfilePictureUrlAttribute(): string
    {
        // Check foto_profil first, then avatar for backward compatibility
        $profilePicture = $this->foto_profil ?? $this->avatar;
        
        if ($profilePicture) {
            // If it's a full URL (like from Google), return as is
            if (filter_var($profilePicture, FILTER_VALIDATE_URL)) {
                return $profilePicture;
            }
            // If it's a local file path
            return asset('storage/' . $profilePicture);
        }
        
        // Return default avatar if no profile picture
        return asset('images/default-avatar.png');
    }

    /**
     * Check if user registered via social provider
     */
    public function isSocialUser(): bool
    {
        return !is_null($this->provider);
    }

    /**
     * Check if user registered via Google
     */
    public function isGoogleUser(): bool
    {
        return $this->provider === 'google';
    }
}