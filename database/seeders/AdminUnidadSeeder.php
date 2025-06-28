<?php

namespace Database\Seeders;

use App\Models\AdminUnidad;
use App\Models\User;
use App\Models\UnidadProductiva;
use Illuminate\Database\Seeder;

class AdminUnidadSeeder extends Seeder
{
    public function run()
    {
        $admins = User::where('role', 'admin')->get();
        $unidades = UnidadProductiva::all();

        // Asignaciones principales (ya están en admin_principal_id)
        $asignaciones = [
            ['admin' => 'María González', 'unidad' => 'Unidad 1 - Avícola'],
            ['admin' => 'Carlos Rodríguez', 'unidad' => 'Unidad 2 - Ganadería'],
            ['admin' => 'Ana Martínez', 'unidad' => 'Unidad 3 - Agricultura'],
            ['admin' => 'Luis Pérez', 'unidad' => 'Unidad 4 - Piscicultura'],
        ];

        foreach ($asignaciones as $asignacion) {
            $admin = $admins->where('name', $asignacion['admin'])->first();
            $unidad = $unidades->where('nombre', $asignacion['unidad'])->first();

            if ($admin && $unidad) {
                AdminUnidad::create([
                    'admin_id' => $admin->id,
                    'unidad_id' => $unidad->id,
                    'fecha_asignacion' => now(),
                    'activo' => true,
                ]);
            }
        }

        // Asignaciones adicionales (admins que apoyan en múltiples unidades)
        // María González también apoya en Ganadería
        $maria = $admins->where('name', 'María González')->first();
        $ganaderia = $unidades->where('nombre', 'Unidad 2 - Ganadería')->first();
        
        if ($maria && $ganaderia) {
            AdminUnidad::create([
                'admin_id' => $maria->id,
                'unidad_id' => $ganaderia->id,
                'fecha_asignacion' => now()->subDays(30),
                'activo' => true,
            ]);
        }
    }
}
