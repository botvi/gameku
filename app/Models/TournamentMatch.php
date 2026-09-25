<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TournamentMatch extends Model
{
    use HasFactory;

    protected $fillable = [
        'tournament_id',
        'round',
        'match_number',
        'player1_id',
        'player2_id',
        'winner_id',
        'status',
        'ready_p1',
        'ready_p2',
        'ready_deadline',
        'room_id',
        'spectator_count',
    ];

    protected $casts = [
        'ready_p1' => 'boolean',
        'ready_p2' => 'boolean',
        'ready_deadline' => 'datetime',
    ];

    public function tournament()
    {
        return $this->belongsTo(Tournament::class, 'tournament_id');
    }

    public function player1()
    {
        return $this->belongsTo(User::class, 'player1_id');
    }

    public function player2()
    {
        return $this->belongsTo(User::class, 'player2_id');
    }

    public function winner()
    {
        return $this->belongsTo(User::class, 'winner_id');
    }
}
