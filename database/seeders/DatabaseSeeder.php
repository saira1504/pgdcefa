<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            UserSeeder::class,
            UnidadProductivaSeeder::class,
            AdminUnidadSeeder::class,
            // AprendizUnidadSeeder::class, // Comentado por ahora
            TareaSeeder::class,
            DocumentoAdminSeeder::class,
            EntregaSeeder::class, // Solo crea directorios por ahora
            PhaseSeeder::class,
        ]);
    }
}
