<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inbox extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'type',
        'target_type',
        'target_user_id',
        'reward_coins',
        'is_active',
    ];

    protected $casts = [
        'reward_coins' => 'integer',
        'is_active' => 'boolean',
    ];

    public function targetUser()
    {
        return $this->belongsTo(User::class, 'target_user_id');
    }

    public function statuses()
    {
        return $this->hasMany(UserInboxStatus::class, 'inbox_id');
    }

    public function statusForUser($userId)
    {
        return $this->hasOne(UserInboxStatus::class, 'inbox_id')->where('user_id', $userId);
    }
}
