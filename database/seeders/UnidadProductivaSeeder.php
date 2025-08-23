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
        // Obtener el primer admin disponible o crear uno si no existe
        $admin = User::where('role', 'admin')->first();
        
        if (!$admin) {
            // Crear un admin por defecto si no existe
            $admin = User::create([
                'name' => 'Admin Principal',
                'email' => 'admin@example.com',
                'password' => bcrypt('password'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]);
        }

        $unidades = [
            [
                'nombre' => 'Unidad 1 - Avícola',
                'descripcion' => 'Unidad productiva especializada en la cría y manejo de aves de corral',
                'tipo' => 'Talento Humano',
                'proyecto' => 'Proyecto de producción y comercialización de huevos y carne de pollo. Formación integral en técnicas modernas de avicultura, manejo sanitario y comercialización de productos avícolas.',
                'objetivos' => 'Formar aprendices en técnicas modernas de avicultura, manejo sanitario y comercialización de productos avícolas.',
                'estado' => 'en_proceso',
                'progreso' => 75,
                'admin_principal_id' => $admin->id,
                'instructor_encargado' => 'Dr. María González',
                'activo' => true,
            ],
            [
                'nombre' => 'Unidad 2 - Ganadería',
                'descripcion' => 'Unidad productiva enfocada en la ganadería bovina y caprina',
                'tipo' => 'Finanzas',
                'proyecto' => 'Proyecto de producción de leche y carne bovina sostenible. Desarrollo de competencias en manejo ganadero, nutrición animal y sistemas de pastoreo rotacional.',
                'objetivos' => 'Desarrollar competencias en manejo ganadero, nutrición animal y sistemas de pastoreo rotacional.',
                'estado' => 'en_proceso',
                'progreso' => 60,
                'admin_principal_id' => $admin->id,
                'instructor_encargado' => 'Ing. Carlos Rodríguez',
                'activo' => true,
            ],
            [
                'nombre' => 'Unidad 3 - Agricultura',
                'descripcion' => 'Unidad productiva de cultivos agrícolas sostenibles',
                'tipo' => 'Operaciones',
                'proyecto' => 'Proyecto de cultivos orgánicos y agricultura de precisión. Capacitación en técnicas de agricultura sostenible, manejo de cultivos y comercialización de productos agrícolas.',
                'objetivos' => 'Capacitar en técnicas de agricultura sostenible, manejo de cultivos y comercialización de productos agrícolas.',
                'estado' => 'iniciando',
                'progreso' => 25,
                'admin_principal_id' => $admin->id,
                'instructor_encargado' => 'MSc. Ana Martínez',
                'activo' => true,
            ],
            [
                'nombre' => 'Unidad 4 - Piscicultura',
                'descripcion' => 'Unidad productiva de cultivo de peces en estanques',
                'tipo' => 'Tecnología',
                'proyecto' => 'Proyecto de piscicultura intensiva y procesamiento de pescado. Formación en técnicas de piscicultura, manejo de calidad del agua y procesamiento de productos pesqueros.',
                'objetivos' => 'Formar en técnicas de piscicultura, manejo de calidad del agua y procesamiento de productos pesqueros.',
                'estado' => 'iniciando',
                'progreso' => 15,
                'admin_principal_id' => $admin->id,
                'instructor_encargado' => 'Dr. Luis Pérez',
                'activo' => true,
            ],
            [
                'nombre' => 'Unidad 5 - Apicultura',
                'descripcion' => 'Unidad productiva de producción de miel y derivados',
                'tipo' => 'Calidad',
                'proyecto' => 'Proyecto de apicultura sostenible y producción de miel orgánica. Formación en manejo de colmenas, producción de miel y derivados apícolas.',
                'objetivos' => 'Formar en técnicas de apicultura, manejo de colmenas y producción de miel orgánica.',
                'estado' => 'iniciando',
                'progreso' => 10,
                'admin_principal_id' => $admin->id,
                'instructor_encargado' => 'Ing. Patricia López',
                'activo' => true,
            ],
        ];

        foreach ($unidades as $unidad) {
            UnidadProductiva::create($unidad);
        }

        $this->command->info('Unidades productivas creadas exitosamente.');
    }
}
