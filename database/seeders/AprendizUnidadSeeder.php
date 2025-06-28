<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UnidadProductiva;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AprendizUnidadSeeder extends Seeder
{
    public function run()
    {
        // Por ahora no asignar aprendices a unidades
        // Esto se hará más adelante cuando se implemente la gestión de aprendices
        
        $this->command->info('Seeder de aprendices preparado para uso futuro.');
        
        /*
        $aprendices = User::where('role', 'aprendiz')->get();
        $unidades = UnidadProductiva::all();

        // Distribución de aprendices por unidad
        $distribuciones = [
            'Unidad 1 - Avícola' => $aprendices->slice(0, 5),
            'Unidad 2 - Ganadería' => $aprendices->slice(5, 5),
            'Unidad 3 - Agricultura' => $aprendices->slice(10, 5),
            'Unidad 4 - Piscicultura' => $aprendices->slice(15, 5),
        ];

        foreach ($distribuciones as $nombreUnidad => $aprendicesUnidad) {
            $unidad = $unidades->where('nombre', $nombreUnidad)->first();
            
            if ($unidad) {
                foreach ($aprendicesUnidad as $aprendiz) {
                    DB::table('aprendiz_unidad')->insert([
                        'aprendiz_id' => $aprendiz->id,
                        'unidad_id' => $unidad->id,
                        'fecha_asignacion' => now()->subDays(rand(30, 180)),
                        'activo' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
        */
    }
}
