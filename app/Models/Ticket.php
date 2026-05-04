<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = ['ticket_number', 'user_id', 'assigned_to_id', 'website_id', 'subject', 'description', 'attachment', 'priority', 'status'];

    protected static function booted()
    {
        static::creating(function ($ticket) {
            $ticket->ticket_number = 'TKT-' . strtoupper(bin2hex(random_bytes(4)));
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

    public function website()
    {
        return $this->belongsTo(Website::class);
    }

    public function messages()
    {
        return $this->hasMany(TicketMessage::class);
    }
}
