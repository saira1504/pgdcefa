<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Phase;
use Carbon\Carbon;

class PhaseSeeder extends Seeder
{
    public function run()
    {
        $phases = [
            [
                'numero' => 1,
                'fecha_inicio' => Carbon::now()->subDays(30),
                'fecha_fin' => Carbon::now()->addDays(15),
            ],
            [
                'numero' => 2,
                'fecha_inicio' => Carbon::now()->addDays(16),
                'fecha_fin' => Carbon::now()->addDays(45),
            ],
            [
                'numero' => 3,
                'fecha_inicio' => Carbon::now()->addDays(46),
                'fecha_fin' => Carbon::now()->addDays(75),
            ],
            [
                'numero' => 4,
                'fecha_inicio' => Carbon::now()->addDays(76),
                'fecha_fin' => Carbon::now()->addDays(105),
            ],
            [
                'numero' => 5,
                'fecha_inicio' => Carbon::now()->addDays(106),
                'fecha_fin' => Carbon::now()->addDays(135),
            ],
        ];

        foreach ($phases as $phase) {
            Phase::create($phase);
        }
    }
} 