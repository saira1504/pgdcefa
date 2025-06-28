<?php

namespace Database\Seeders;

use App\Models\Tarea;
use App\Models\User;
use App\Models\UnidadProductiva;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class TareaSeeder extends Seeder
{
    public function run()
    {
        $admins = User::where('role', 'admin')->get();
        $unidades = UnidadProductiva::all();

        $tareasPorUnidad = [
            'Unidad 1 - Avícola' => [
                [
                    'titulo' => 'Informe de Manejo Sanitario',
                    'descripcion' => 'Elaborar un informe detallado sobre las prácticas de manejo sanitario en la granja avícola',
                    'tipo' => 'entrega',
                    'prioridad' => 'alta',
                    'fecha_limite' => Carbon::now()->addDays(7),
                    'instrucciones' => 'El informe debe incluir: protocolos de bioseguridad, programa de vacunación, y medidas preventivas.',
                ],
                [
                    'titulo' => 'Evaluación de Nutrición Avícola',
                    'descripcion' => 'Realizar evaluación práctica sobre formulación de dietas para aves',
                    'tipo' => 'evaluacion',
                    'prioridad' => 'normal',
                    'fecha_limite' => Carbon::now()->addDays(14),
                    'instrucciones' => 'Calcular requerimientos nutricionales para diferentes etapas de crecimiento.',
                ],
            ],
            'Unidad 2 - Ganadería' => [
                [
                    'titulo' => 'Plan de Pastoreo Rotacional',
                    'descripcion' => 'Diseñar un plan de pastoreo rotacional para 50 cabezas de ganado',
                    'tipo' => 'entrega',
                    'prioridad' => 'alta',
                    'fecha_limite' => Carbon::now()->addDays(10),
                    'instrucciones' => 'Incluir cálculo de carga animal, rotación de potreros y cronograma.',
                ],
            ],
            'Unidad 3 - Agricultura' => [
                [
                    'titulo' => 'Análisis de Suelos',
                    'descripcion' => 'Realizar análisis físico-químico de suelos del lote asignado',
                    'tipo' => 'entrega',
                    'prioridad' => 'urgente',
                    'fecha_limite' => Carbon::now()->addDays(3),
                    'instrucciones' => 'Tomar muestras, analizar pH, materia orgánica y nutrientes disponibles.',
                ],
            ],
            'Unidad 4 - Piscicultura' => [
                [
                    'titulo' => 'Monitoreo de Calidad del Agua',
                    'descripcion' => 'Realizar monitoreo diario de parámetros de calidad del agua',
                    'tipo' => 'practica',
                    'prioridad' => 'alta',
                    'fecha_limite' => Carbon::now()->addDays(6),
                    'instrucciones' => 'Medir pH, oxígeno disuelto, temperatura y turbidez.',
                ],
            ],
        ];

        foreach ($tareasPorUnidad as $nombreUnidad => $tareas) {
            $unidad = $unidades->where('nombre', $nombreUnidad)->first();
            if (!$unidad) continue;
            
            $admin = $admins->where('id', $unidad->admin_principal_id)->first();
            if (!$admin) continue;

            foreach ($tareas as $tareaData) {
                Tarea::create([
                    'admin_id' => $admin->id,
                    'unidad_id' => $unidad->id,
                    'aprendiz_id' => null, // Tarea para toda la unidad
                    'titulo' => $tareaData['titulo'],
                    'descripcion' => $tareaData['descripcion'],
                    'tipo' => $tareaData['tipo'],
                    'prioridad' => $tareaData['prioridad'],
                    'fecha_limite' => $tareaData['fecha_limite'],
                    'instrucciones' => $tareaData['instrucciones'],
                    'estado' => 'pendiente',
                ]);
            }
        }
    }
}
