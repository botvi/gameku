<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserInboxStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'inbox_id',
        'user_id',
        'is_read',
        'is_claimed',
        'is_deleted',
        'read_at',
        'claimed_at',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'is_claimed' => 'boolean',
        'is_deleted' => 'boolean',
        'read_at' => 'datetime',
        'claimed_at' => 'datetime',
    ];

    public function inbox()
    {
        return $this->belongsTo(Inbox::class, 'inbox_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
