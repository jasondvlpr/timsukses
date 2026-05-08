<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebsiteRequest extends Model
{
    use HasFactory;

    protected $fillable = ['ticket_number', 'user_id', 'assigned_to_id', 'name', 'url', 'description', 'status', 'admin_note', 'is_forwarded'];

    protected static function booted()
    {
        static::creating(function ($request) {
            $request->ticket_number = 'REQ-' . strtoupper(bin2hex(random_bytes(4)));
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to_id');
    }
}
