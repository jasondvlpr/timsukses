<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'whatsapp',
        'password',
        'role',
        'last_active_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password'          => 'hashed',
            'last_active_at'    => 'datetime',
        ];
    }

    // ─── Role Helpers ────────────────────────────────────────────
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isStaff()
    {
        return $this->role === 'staff';
    }

    public function isPromoter()
    {
        return $this->role === 'promoter';
    }

    // ─── Online Status ───────────────────────────────────────────
    /**
     * Consider user online if active within the last 5 minutes.
     */
    public function isOnline(): bool
    {
        return $this->last_active_at
            && $this->last_active_at->greaterThan(now()->subMinutes(5));
    }

    // ─── Relationships ───────────────────────────────────────────
    public function websites()
    {
        return $this->hasMany(Website::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function websiteRequests()
    {
        return $this->hasMany(WebsiteRequest::class);
    }

    public function sentMessages()
    {
        return $this->hasMany(ChatMessage::class, 'user_id');
    }
}
