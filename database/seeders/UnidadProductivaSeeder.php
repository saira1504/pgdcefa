<?php

namespace Database\Seeders;

use App\Models\UnidadProductiva;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class UnidadProductivaSeeder extends Seeder
{
    public function run()
    {
        $admins = User::where('role', 'admin')->get();

        $unidades = [
            [
                'nombre' => 'Unidad 1 - Avícola',
                'descripcion' => 'Unidad productiva especializada en la cría y manejo de aves de corral',
                'tipo' => 'avicola',
                'proyecto' => 'Proyecto de producción y comercialización de huevos y carne de pollo',
                'objetivos' => 'Formar aprendices en técnicas modernas de avicultura, manejo sanitario y comercialización de productos avícolas.',
                'fecha_inicio' => Carbon::now()->subMonths(6),
                'fecha_fin' => Carbon::now()->addMonths(18),
                'estado' => 'en_proceso',
                'progreso' => 75,
                'admin_principal_id' => $admins->where('name', 'María González')->first()->id,
            ],
            [
                'nombre' => 'Unidad 2 - Ganadería',
                'descripcion' => 'Unidad productiva enfocada en la ganadería bovina y caprina',
                'tipo' => 'ganaderia',
                'proyecto' => 'Proyecto de producción de leche y carne bovina sostenible',
                'objetivos' => 'Desarrollar competencias en manejo ganadero, nutrición animal y sistemas de pastoreo rotacional.',
                'fecha_inicio' => Carbon::now()->subMonths(4),
                'fecha_fin' => Carbon::now()->addMonths(20),
                'estado' => 'en_proceso',
                'progreso' => 60,
                'admin_principal_id' => $admins->where('name', 'Carlos Rodríguez')->first()->id,
            ],
            [
                'nombre' => 'Unidad 3 - Agricultura',
                'descripcion' => 'Unidad productiva de cultivos agrícolas sostenibles',
                'tipo' => 'agricultura',
                'proyecto' => 'Proyecto de cultivos orgánicos y agricultura de precisión',
                'objetivos' => 'Capacitar en técnicas de agricultura sostenible, manejo de cultivos y comercialización de productos agrícolas.',
                'fecha_inicio' => Carbon::now()->subMonths(2),
                'fecha_fin' => Carbon::now()->addMonths(22),
                'estado' => 'iniciando',
                'progreso' => 25,
                'admin_principal_id' => $admins->where('name', 'Ana Martínez')->first()->id,
            ],
            [
                'nombre' => 'Unidad 4 - Piscicultura',
                'descripcion' => 'Unidad productiva de cultivo de peces en estanques',
                'tipo' => 'piscicultura',
                'proyecto' => 'Proyecto de piscicultura intensiva y procesamiento de pescado',
                'objetivos' => 'Formar en técnicas de piscicultura, manejo de calidad del agua y procesamiento de productos pesqueros.',
                'fecha_inicio' => Carbon::now()->subMonth(1),
                'fecha_fin' => Carbon::now()->addMonths(23),
                'estado' => 'iniciando',
                'progreso' => 15,
                'admin_principal_id' => $admins->where('name', 'Luis Pérez')->first()->id,
            ],
        ];

        foreach ($unidades as $unidad) {
            UnidadProductiva::create($unidad);
        }
    }
}
