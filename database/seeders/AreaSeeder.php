<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Area;

class AreaSeeder extends Seeder
{
    public function run(): void
    {
        // Solo verificar que las áreas existan, no crear nuevas
        $areas = [
            'Talento Humano',
            'Finanzas', 
            'Operaciones',
            'Tecnología',
            'Calidad',
            'Ambiental',
            'Comercial',
            'Legal',
            'Area Ambiental'
        ];

        foreach ($areas as $nombre) {
            // Solo crear si no existe
            Area::firstOrCreate(
                ['nombre' => $nombre],
                [
                    'nombre' => $nombre,
                    'descripcion' => 'Descripción de ' . $nombre,
                    'activo' => true,
                ]
            );
        }

        $this->command->info('Áreas verificadas. Total: ' . Area::count());
    }
}
