<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ListadoMaestro;
use App\Models\User;

class ListadoMaestroSeeder extends Seeder
{
    public function run()
    {
        // Obtener un admin para asignar como creador
        $admin = User::where('role', 'admin')->first();
        
        if (!$admin) {
            // Si no hay admin, crear uno
            $admin = User::create([
                'name' => 'Admin Test',
                'email' => 'admin@test.com',
                'password' => bcrypt('password'),
                'role' => 'admin'
            ]);
        }

        $documentos = [
            [
                'tipo_proceso' => 'Gestión de Calidad',
                'nombre_proceso' => 'Control de Calidad',
                'subproceso_sig_subsistema' => 'SIG Calidad',
                'documentos' => 'manual_calidad_v1.0.pdf',
                'numero_doc' => 'DOC-001',
                'responsable' => 'Juan Pérez',
                'tipo_documento' => 'Manual',
                'nombre_documento' => 'Manual de Calidad',
                'codigo' => 'MC-001',
                'version' => '1.0',
                'fecha_creacion' => '2025-01-15',
                'estado' => 'pendiente',
                'creado_por' => $admin->id
            ],
            [
                'tipo_proceso' => 'Recursos Humanos',
                'nombre_proceso' => 'Selección de Personal',
                'subproceso_sig_subsistema' => 'RRHH',
                'documentos' => 'proceso_seleccion_v2.1.pdf',
                'numero_doc' => 'DOC-002',
                'responsable' => 'María García',
                'tipo_documento' => 'Procedimiento',
                'nombre_documento' => 'Procedimiento de Selección',
                'codigo' => 'PS-002',
                'version' => '2.1',
                'fecha_creacion' => '2025-01-20',
                'estado' => 'aprobado',
                'aprobacion_fecha' => '2025-01-25',
                'aprobacion_cargo' => 'Superadmin',
                'aprobacion_firma' => 'Admin Principal',
                'creado_por' => $admin->id
            ],
            [
                'tipo_proceso' => 'Finanzas',
                'nombre_proceso' => 'Control Presupuestario',
                'subproceso_sig_subsistema' => 'SIG Financiero',
                'documentos' => 'control_presupuesto_v1.5.pdf',
                'numero_doc' => 'DOC-003',
                'responsable' => 'Carlos López',
                'tipo_documento' => 'Procedimiento',
                'nombre_documento' => 'Control Presupuestario',
                'codigo' => 'CP-003',
                'version' => '1.5',
                'fecha_creacion' => '2025-01-18',
                'estado' => 'rechazado',
                'revision_fecha' => '2025-01-22',
                'revision_cargo' => 'Superadmin',
                'revision_firma' => 'Admin Principal',
                'creado_por' => $admin->id
            ],
            [
                'tipo_proceso' => 'Operaciones',
                'nombre_proceso' => 'Mantenimiento Preventivo',
                'subproceso_sig_subsistema' => 'SIG Operativo',
                'documentos' => 'mantenimiento_preventivo_v1.2.pdf',
                'numero_doc' => 'DOC-004',
                'responsable' => 'Ana Rodríguez',
                'tipo_documento' => 'Instrucción',
                'nombre_documento' => 'Mantenimiento Preventivo',
                'codigo' => 'MP-004',
                'version' => '1.2',
                'fecha_creacion' => '2025-01-30',
                'estado' => 'pendiente',
                'creado_por' => $admin->id
            ]
        ];

        foreach ($documentos as $documento) {
            ListadoMaestro::create($documento);
        }
    }
}
