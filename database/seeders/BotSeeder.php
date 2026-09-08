<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\ModelJalur;
use Illuminate\Support\Facades\Hash;

class BotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Buat user bot AI dengan nama random untuk matchmaking.
     */
    public function run(): void
    {
        $botNames = [
            'JalurMaster',
            'PacuPro',
            'RapidRower',
            'SpeedBoat',
            'RiverKing',
            'PaddleWizard',
            'WaveRider',
            'BoatBoss',
            'CurrentChamp',
            'SplashStar',
            'TurboJalur',
            'AquaAce',
            'StreamSultan',
            'FlowFighter',
            'RapidsRider',
            'CanalCrusher',
            'DriftDuke',
            'PaddlePhenom',
            'RiverRocket',
            'BoatBlitz',
        ];

        $hairColors = ['#111827', '#F59E0B', '#DC2626', '#7C3AED', '#2563EB', '#EC4899'];
        $shirtColors = ['#10B981', '#F97316', '#EF4444', '#8B5CF6', '#3B82F6', '#EC4899'];
        $boatColors = ['#8D6E63', '#B45309', '#1F2937', '#7C3AED', '#0E7490', '#BE123C'];

        foreach ($botNames as $idx => $name) {
            $email = 'bot_' . strtolower($name) . '@pacu.ai';

            $user = User::updateOrCreate(
                ['email' => $email],
                [
                    'nama_jalur' => $name,
                    'kuansing_poin' => 100,
                    'password' => Hash::make('botpassword'),
                    'role' => 'bot',
                    'is_bot' => true,
                    'foto_profile' => null,
                ]
            );

            // Buat model jalur random untuk bot
            $modelJalur = ModelJalur::where('user_id', $user->id)->first();
            if (!$modelJalur) {
                ModelJalur::create([
                    'user_id' => $user->id,
                    'model_jalur' => [
                        'customColors' => [
                            'boat' => $boatColors[$idx % count($boatColors)],
                            'hair' => $hairColors[$idx % count($hairColors)],
                            'pants' => '#38a169',
                            'shirt' => $shirtColors[$idx % count($shirtColors)],
                            'paddle' => '#8D6E63',
                            'splash' => '#a5f3fc',
                        ],
                        'boat_unlocked' => false,
                        'corak_data_url' => null,
                        'lambai_data_url' => null,
                        'lambai_unlocked' => false,
                    ],
                    'fitur_corak' => 'inactive',
                    'fitur_lambai' => 'inactive',
                ]);
            }
        }
    }
}