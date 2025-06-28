<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Superadministrador
        User::create([
            'name' => 'Super Administrador',
            'email' => 'superadmin@sena.edu.co',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'role' => 'superadmin',
        ]);

        // Administradores
        $admins = [
            [
                'name' => 'María González',
                'email' => 'maria.gonzalez@sena.edu.co',
                'role' => 'admin',
            ],
            [
                'name' => 'Carlos Rodríguez',
                'email' => 'carlos.rodriguez@sena.edu.co',
                'role' => 'admin',
            ],
            [
                'name' => 'Ana Martínez',
                'email' => 'ana.martinez@sena.edu.co',
                'role' => 'admin',
            ],
            [
                'name' => 'Luis Pérez',
                'email' => 'luis.perez@sena.edu.co',
                'role' => 'admin',
            ],
        ];

        foreach ($admins as $admin) {
            User::create([
                'name' => $admin['name'],
                'email' => $admin['email'],
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'role' => $admin['role'],
            ]);
        }

        // Aprendices (creados pero no asignados a unidades aún)
        $aprendices = [
            // Para uso futuro cuando se implemente la gestión de aprendices
            ['name' => 'Juan Pérez', 'email' => 'juan.perez@aprendiz.sena.edu.co'],
            ['name' => 'Laura Gómez', 'email' => 'laura.gomez@aprendiz.sena.edu.co'],
            ['name' => 'Diego Morales', 'email' => 'diego.morales@aprendiz.sena.edu.co'],
            ['name' => 'Sofía Herrera', 'email' => 'sofia.herrera@aprendiz.sena.edu.co'],
            ['name' => 'Andrés Castro', 'email' => 'andres.castro@aprendiz.sena.edu.co'],
            ['name' => 'Camila Ruiz', 'email' => 'camila.ruiz@aprendiz.sena.edu.co'],
            ['name' => 'Sebastián López', 'email' => 'sebastian.lopez@aprendiz.sena.edu.co'],
            ['name' => 'Valentina Torres', 'email' => 'valentina.torres@aprendiz.sena.edu.co'],
            ['name' => 'Mateo Vargas', 'email' => 'mateo.vargas@aprendiz.sena.edu.co'],
            ['name' => 'Isabella Jiménez', 'email' => 'isabella.jimenez@aprendiz.sena.edu.co'],
            ['name' => 'Santiago Ramírez', 'email' => 'santiago.ramirez@aprendiz.sena.edu.co'],
            ['name' => 'Mariana Ospina', 'email' => 'mariana.ospina@aprendiz.sena.edu.co'],
            ['name' => 'Nicolás Mejía', 'email' => 'nicolas.mejia@aprendiz.sena.edu.co'],
            ['name' => 'Gabriela Sánchez', 'email' => 'gabriela.sanchez@aprendiz.sena.edu.co'],
            ['name' => 'Alejandro Díaz', 'email' => 'alejandro.diaz@aprendiz.sena.edu.co'],
            ['name' => 'Daniela Rojas', 'email' => 'daniela.rojas@aprendiz.sena.edu.co'],
            ['name' => 'Felipe Cardona', 'email' => 'felipe.cardona@aprendiz.sena.edu.co'],
            ['name' => 'Natalia Vega', 'email' => 'natalia.vega@aprendiz.sena.edu.co'],
            ['name' => 'Tomás Aguilar', 'email' => 'tomas.aguilar@aprendiz.sena.edu.co'],
            ['name' => 'Lucía Mendoza', 'email' => 'lucia.mendoza@aprendiz.sena.edu.co'],
        ];

        foreach ($aprendices as $aprendiz) {
            User::create([
                'name' => $aprendiz['name'],
                'email' => $aprendiz['email'],
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'role' => 'aprendiz',
            ]);
        }

        $this->command->info('✅ Usuarios creados:');
        $this->command->info('   - 1 Superadmin: superadmin@sena.edu.co');
        $this->command->info('   - 4 Admins: maria.gonzalez@sena.edu.co, carlos.rodriguez@sena.edu.co, etc.');
        $this->command->info('   - 20 Aprendices: (para gestión futura)');
        $this->command->info('   - Password para todos: password');
    }
}
