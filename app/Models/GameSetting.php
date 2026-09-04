<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameSetting extends Model
{
    use HasFactory;

    protected $table = 'game_settings';

    protected $fillable = [
        'fullscreen',
    ];

    protected $casts = [
        'fullscreen' => 'boolean',
    ];

    /**
     * Get or create the singleton game setting instance.
     */
    public static function instance(): self
    {
        return self::firstOrCreate(
            ['id' => 1],
            ['fullscreen' => 1]
        );
    }

    /**
     * Check if auto fullscreen is enabled (1 = ON, 0 = OFF).
     */
    public static function isFullscreenEnabled(): bool
    {
        $setting = self::first();
        if (!$setting) {
            return true; // Default to true if not set
        }
        return (bool) $setting->fullscreen;
    }
}
