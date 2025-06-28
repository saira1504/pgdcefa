<?php

namespace Database\Seeders;

use App\Models\Entrega;
use App\Models\Tarea;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class EntregaSeeder extends Seeder
{
    public function run()
    {
        // Crear directorio para entregas si no existe
        if (!Storage::disk('public')->exists('entregas')) {
            Storage::disk('public')->makeDirectory('entregas');
        }

        $aprendices = User::where('role', 'aprendiz')->get();
        $tareas = Tarea::where('tipo', 'entrega')->get();

        // Estados posibles para las entregas
        $estadosPosibles = ['entregado', 'en_revision', 'aprobado'];
        $calificacionesPosibles = [2.5, 3.0, 3.5, 4.0, 4.2, 4.5, 4.8, 5.0];

        $comentariosAprendiz = [
            'Adjunto el informe solicitado con todos los puntos desarrollados.',
            'He completado la tarea según las instrucciones proporcionadas.',
            'Documento elaborado con base en la investigación realizada en campo.',
            'Entrego el trabajo final después de las correcciones sugeridas.',
            'Informe desarrollado con datos reales de la unidad productiva.',
            'Adjunto evidencias fotográficas y registros de campo.',
            'Trabajo realizado en equipo con mis compañeros de unidad.',
            'Documento que incluye análisis y propuestas de mejora.',
        ];

        $retroalimentacionAdmin = [
            'Excelente trabajo. Cumple con todos los objetivos planteados.',
            'Buen desarrollo del tema. Se sugiere profundizar en el análisis de resultados.',
            'Trabajo satisfactorio. Incluir más referencias bibliográficas.',
            'Muy bien estructurado. Las conclusiones son coherentes con el desarrollo.',
            'Faltan algunos elementos solicitados. Revisar las instrucciones.',
            'Trabajo incompleto. Es necesario rehacer algunos apartados.',
            'Excelente presentación y contenido técnico apropiado.',
            'Buena investigación de campo. Mejorar la redacción en algunos párrafos.',
            'Cumple con los requisitos mínimos. Puede mejorar la profundidad del análisis.',
            'Trabajo destacado. Demuestra comprensión clara de los conceptos.',
        ];

        foreach ($tareas as $tarea) {
            // Obtener aprendices de la unidad de la tarea
            $aprendicesUnidad = $aprendices->filter(function($aprendiz) use ($tarea) {
                return $aprendiz->aprendizUnidad()
                    ->where('unidad_id', $tarea->unidad_id)
                    ->where('activo', true)
                    ->exists();
            });

            // Si la tarea es para un aprendiz específico
            if ($tarea->aprendiz_id) {
                $aprendicesUnidad = $aprendices->where('id', $tarea->aprendiz_id);
            }

            // Crear entregas para algunos aprendices (no todos entregan siempre)
            $aprendicesQueEntregan = $aprendicesUnidad->random(min(3, $aprendicesUnidad->count()));

            foreach ($aprendicesQueEntregan as $aprendiz) {
                // Determinar estado de la entrega basado en la fecha límite
                $estado = $this->determinarEstadoEntrega($tarea);
                
                // Crear archivo de entrega simulado
                $nombreArchivo = "entrega_{$tarea->id}_{$aprendiz->id}_" . uniqid() . '.pdf';
                $archivoPath = 'entregas/' . $nombreArchivo;
                
                $contenidoEntrega = "ENTREGA - {$tarea->titulo}\n";
                $contenidoEntrega .= "Aprendiz: {$aprendiz->name}\n";
                $contenidoEntrega .= "Unidad: {$tarea->unidad->nombre}\n";
                $contenidoEntrega .= "Fecha de entrega: " . now()->format('Y-m-d H:i:s') . "\n\n";
                $contenidoEntrega .= "Desarrollo de la tarea:\n";
                $contenidoEntrega .= "Lorem ipsum dolor sit amet, consectetur adipiscing elit. ";
                $contenidoEntrega .= "Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.\n";
                $contenidoEntrega .= "Ut enim ad minim veniam, quis nostrud exercitation ullamco.\n\n";
                $contenidoEntrega .= "Conclusiones:\n";
                $contenidoEntrega .= "- Punto 1 de conclusión\n";
                $contenidoEntrega .= "- Punto 2 de conclusión\n";
                $contenidoEntrega .= "- Punto 3 de conclusión\n";

                Storage::disk('public')->put($archivoPath, $contenidoEntrega);

                // Determinar fechas
                $fechaEntrega = $this->generarFechaEntrega($tarea);
                $fechaRevision = null;
                $calificacion = null;
                $retroalimentacion = null;

                // Si está revisado o aprobado, agregar calificación y retroalimentación
                if (in_array($estado, ['aprobado'])) {
                    $fechaRevision = $fechaEntrega->addDays(rand(1, 3));
                    
                    if ($estado === 'aprobado') {
                        $calificacion = collect($calificacionesPosibles)->random();
                        $retroalimentacion = collect($retroalimentacionAdmin)->random();
                    }
                }

                Entrega::create([
                    'tarea_id' => $tarea->id,
                    'aprendiz_id' => $aprendiz->id,
                    'archivo_path' => $archivoPath,
                    'archivo_original' => "entrega_{$tarea->titulo}_{$aprendiz->name}.pdf",
                    'comentarios' => collect($comentariosAprendiz)->random(),
                    'calificacion' => $calificacion,
                    'retroalimentacion' => $retroalimentacion,
                    'estado' => $estado,
                    'fecha_entrega' => $fechaEntrega,
                    'fecha_revision' => $fechaRevision,
                ]);

                // Actualizar estado de la tarea si es necesario
                if ($estado === 'aprobado' && $tarea->aprendiz_id === $aprendiz->id) {
                    $tarea->update(['estado' => 'aprobado']);
                }
            }
        }

        // Crear algunas entregas adicionales con casos especiales
        $this->crearEntregasEspeciales();
    }

    private function determinarEstadoEntrega($tarea)
    {
        $diasHastaLimite = $tarea->fecha_limite ? now()->diffInDays($tarea->fecha_limite, false) : 10;
        
        if ($diasHastaLimite < -5) {
            // Tarea muy vencida - más probabilidad de estar sin entregar o rechazada
            return collect(['entregado', 'rechazado'])->random();
        } elseif ($diasHastaLimite < 0) {
            // Tarea vencida - mezcla de estados
            return collect(['entregado', 'en_revision', 'aprobado', 'rechazado'])->random();
        } else {
            // Tarea vigente - más probabilidad de estar aprobada o en revisión
            return collect(['entregado', 'en_revision', 'aprobado'])->random();
        }
    }

    private function generarFechaEntrega($tarea)
    {
        if ($tarea->fecha_limite) {
            // Generar fecha entre la creación de la tarea y la fecha límite
            $inicio = $tarea->created_at;
            $fin = $tarea->fecha_limite;
            
            $diasDiferencia = $inicio->diffInDays($fin);
            $diasRandom = rand(0, max(1, $diasDiferencia));
            
            return $inicio->addDays($diasRandom);
        }
        
        // Si no hay fecha límite, generar fecha reciente
        return now()->subDays(rand(1, 10));
    }

    private function crearEntregasEspeciales()
    {
        // Crear una entrega tardía
        $tareaVencida = Tarea::where('fecha_limite', '<', now())
            ->where('tipo', 'entrega')
            ->first();

        if ($tareaVencida) {
            $aprendizTarde = User::where('role', 'aprendiz')
                ->whereHas('aprendizUnidad', function($query) use ($tareaVencida) {
                    $query->where('unidad_id', $tareaVencida->unidad_id);
                })
                ->first();

            if ($aprendizTarde) {
                $archivoTarde = 'entregas/entrega_tardia_' . uniqid() . '.pdf';
                Storage::disk('public')->put($archivoTarde, 'Entrega realizada después de la fecha límite');

                Entrega::create([
                    'tarea_id' => $tareaVencida->id,
                    'aprendiz_id' => $aprendizTarde->id,
                    'archivo_path' => $archivoTarde,
                    'archivo_original' => 'entrega_tardia.pdf',
                    'comentarios' => 'Disculpe la demora. Adjunto la entrega solicitada.',
                    'calificacion' => 2.0,
                    'retroalimentacion' => 'Entrega tardía. El contenido es aceptable pero se penaliza por la demora.',
                    'estado' => 'aprobado',
                    'fecha_entrega' => $tareaVencida->fecha_limite->addDays(3),
                    'fecha_revision' => $tareaVencida->fecha_limite->addDays(5),
                ]);
            }
        }

        // Crear una entrega que requiere reentrega
        $tareaReentrega = Tarea::where('tipo', 'entrega')->skip(2)->first();
        if ($tareaReentrega) {
            $aprendizReentrega = User::where('role', 'aprendiz')
                ->whereHas('aprendizUnidad', function($query) use ($tareaReentrega) {
                    $query->where('unidad_id', $tareaReentrega->unidad_id);
                })
                ->first();

            if ($aprendizReentrega) {
                $archivoReentrega = 'entregas/reentrega_' . uniqid() . '.pdf';
                Storage::disk('public')->put($archivoReentrega, 'Segunda versión de la entrega con correcciones');

                Entrega::create([
                    'tarea_id' => $tareaReentrega->id,
                    'aprendiz_id' => $aprendizReentrega->id,
                    'archivo_path' => $archivoReentrega,
                    'archivo_original' => 'entrega_corregida_v2.pdf',
                    'comentarios' => 'Adjunto la versión corregida según las observaciones realizadas.',
                    'calificacion' => 4.0,
                    'retroalimentacion' => 'Mucho mejor. Las correcciones fueron implementadas correctamente.',
                    'estado' => 'aprobado',
                    'fecha_entrega' => now()->subDays(2),
                    'fecha_revision' => now()->subDays(1),
                ]);
            }
        }
    }
}
