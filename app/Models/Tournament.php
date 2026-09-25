<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tournament extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'max_participants',
        'status',
        'prize_coins',
        'winner_id',
        'runner_up_id',
        'created_by',
        'started_at',
        'completed_at',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function winner()
    {
        return $this->belongsTo(User::class, 'winner_id');
    }

    public function runnerUp()
    {
        return $this->belongsTo(User::class, 'runner_up_id');
    }

    public function participants()
    {
        return $this->hasMany(TournamentParticipant::class, 'tournament_id');
    }

    public function matches()
    {
        return $this->hasMany(TournamentMatch::class, 'tournament_id');
    }

    public function winners()
    {
        return $this->hasMany(TournamentWinner::class, 'tournament_id');
    }
}
