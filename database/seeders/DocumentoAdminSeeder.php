<?php

namespace Database\Seeders;

use App\Models\DocumentoAdmin;
use App\Models\User;
use App\Models\UnidadProductiva;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class DocumentoAdminSeeder extends Seeder
{
    public function run()
    {
        $admins = User::where('role', 'admin')->get();
        $unidades = UnidadProductiva::all();

        // Crear directorio para documentos de prueba si no existe
        if (!Storage::disk('public')->exists('documentos/admin')) {
            Storage::disk('public')->makeDirectory('documentos/admin');
        }

        $documentosPorUnidad = [
            'Unidad 1 - Avícola' => [
                [
                    'titulo' => 'Manual de Manejo Avícola',
                    'descripcion' => 'Guía completa para el manejo sanitario y productivo de aves de corral',
                    'tipo_documento' => 'manual',
                    'categoria' => 'teoria',
                    'archivo_original' => 'manual_avicola.pdf',
                    'mime_type' => 'application/pdf',
                    'tamaño_archivo' => 2048576, // 2MB
                    'prioridad' => 'alta',
                    'requiere_entrega' => false,
                    'notificar_aprendices' => true,
                ],
                [
                    'titulo' => 'Guía de Bioseguridad',
                    'descripcion' => 'Protocolos de bioseguridad para granjas avícolas',
                    'tipo_documento' => 'guia',
                    'categoria' => 'practica',
                    'archivo_original' => 'bioseguridad_avicola.pdf',
                    'mime_type' => 'application/pdf',
                    'tamaño_archivo' => 1536000, // 1.5MB
                    'prioridad' => 'urgente',
                    'requiere_entrega' => true,
                    'notificar_aprendices' => true,
                    'fecha_limite' => now()->addDays(7),
                ],
            ],
            'Unidad 2 - Ganadería' => [
                [
                    'titulo' => 'Manual de Ganadería Sostenible',
                    'descripcion' => 'Técnicas modernas de ganadería bovina sostenible',
                    'tipo_documento' => 'manual',
                    'categoria' => 'teoria',
                    'archivo_original' => 'manual_ganaderia.pdf',
                    'mime_type' => 'application/pdf',
                    'tamaño_archivo' => 3072000, // 3MB
                    'prioridad' => 'normal',
                    'requiere_entrega' => false,
                    'notificar_aprendices' => true,
                ],
            ],
        ];

        foreach ($documentosPorUnidad as $nombreUnidad => $documentos) {
            $unidad = $unidades->where('nombre', $nombreUnidad)->first();
            if (!$unidad) continue;
            
            $admin = $admins->where('id', $unidad->admin_principal_id)->first();
            if (!$admin) continue;

            foreach ($documentos as $docData) {
                // Crear archivo de prueba (simulado)
                $archivoPath = 'documentos/admin/' . uniqid() . '_' . $docData['archivo_original'];
                
                // Crear contenido de prueba para el archivo
                $contenidoPrueba = "Documento de prueba: {$docData['titulo']}\n";
                $contenidoPrueba .= "Unidad: {$nombreUnidad}\n";
                $contenidoPrueba .= "Descripción: {$docData['descripcion']}\n";
                $contenidoPrueba .= "Fecha de creación: " . now()->format('Y-m-d H:i:s');
                
                Storage::disk('public')->put($archivoPath, $contenidoPrueba);

                DocumentoAdmin::create([
                    'admin_id' => $admin->id,
                    'unidad_id' => $unidad->id,
                    'titulo' => $docData['titulo'],
                    'descripcion' => $docData['descripcion'],
                    'tipo_documento' => $docData['tipo_documento'],
                    'categoria' => $docData['categoria'],
                    'archivo_path' => $archivoPath,
                    'archivo_original' => $docData['archivo_original'],
                    'mime_type' => $docData['mime_type'],
                    'tamaño_archivo' => $docData['tamaño_archivo'],
                    'prioridad' => $docData['prioridad'],
                    'requiere_entrega' => $docData['requiere_entrega'],
                    'notificar_aprendices' => $docData['notificar_aprendices'],
                    'fecha_limite' => $docData['fecha_limite'] ?? null,
                    'activo' => true,
                    'descargas' => rand(0, 15), // Simular algunas descargas
                ]);
            }
        }
    }
}
