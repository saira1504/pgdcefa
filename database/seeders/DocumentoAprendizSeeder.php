<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DocumentoAprendiz;
use App\Models\User;
use App\Models\UnidadProductiva;
use App\Models\Phase;
use App\Models\TipoDocumento;

class DocumentoAprendizSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener usuarios aprendices
        $aprendices = User::where('role', 'aprendiz')->get();
        
        // Obtener unidades productivas
        $unidades = UnidadProductiva::all();
        
        // Obtener fases
        $fases = Phase::all();
        
        // Obtener tipos de documento
        $tiposDocumento = TipoDocumento::all();
        
        if ($aprendices->isEmpty() || $unidades->isEmpty() || $fases->isEmpty()) {
            $this->command->warn('No hay aprendices, unidades o fases para crear documentos. Ejecuta primero los seeders correspondientes.');
            return;
        }

        $estados = ['pendiente', 'en_revision', 'aprobado', 'rechazado'];
        $tiposArchivo = [
            'application/pdf' => 'documento.pdf',
            'application/msword' => 'informe.doc',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'reporte.docx',
            'application/vnd.ms-excel' => 'datos.xls',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'estadisticas.xlsx'
        ];

        $comentariosRechazo = [
            'El documento no cumple con los requisitos especificados.',
            'Faltan datos importantes en el análisis.',
            'Por favor, incluye información más detallada.',
            'El formato no es el correcto para este tipo de documento.',
            'Necesitas revisar la ortografía y gramática.'
        ];

        $this->command->info('Creando documentos de aprendices...');

        foreach ($aprendices as $aprendiz) {
            // Asignar el aprendiz a una unidad aleatoria si no tiene
            if (!$aprendiz->unidadAsignada) {
                $unidad = $unidades->random();
                $aprendiz->aprendizUnidad()->syncWithoutDetaching([
                    $unidad->id => ['fecha_asignacion' => now(), 'activo' => true]
                ]);
            }

            $unidadAsignada = $aprendiz->unidadAsignada;
            if (!$unidadAsignada) continue;

            // Crear 2-5 documentos por aprendiz
            $numDocumentos = rand(2, 5);
            
            for ($i = 0; $i < $numDocumentos; $i++) {
                $fase = $fases->random();
                $estado = $estados[array_rand($estados)];
                $tipoArchivo = array_rand($tiposArchivo);
                $archivoOriginal = $tiposArchivo[$tipoArchivo];
                
                $documento = DocumentoAprendiz::create([
                    'aprendiz_id' => $aprendiz->id,
                    'unidad_id' => $unidadAsignada->id,
                    'tipo_documento_id' => $fase->id,
                    'titulo' => $this->generarTitulo($i + 1, $fase->numero),
                    'descripcion' => $this->generarDescripcion($i + 1, $fase->numero),
                    'archivo_path' => 'documentos/aprendiz/' . $aprendiz->id . '/' . $archivoOriginal,
                    'archivo_original' => $archivoOriginal,
                    'mime_type' => $tipoArchivo,
                    'tamaño_archivo' => rand(50000, 5000000), // 50KB a 5MB
                    'estado' => $estado,
                    'comentarios_rechazo' => $estado === 'rechazado' ? $comentariosRechazo[array_rand($comentariosRechazo)] : null,
                    'fecha_subida' => now()->subDays(rand(1, 30)),
                    'fecha_revision' => $estado !== 'pendiente' ? now()->subDays(rand(1, 15)) : null,
                    'revisado_por' => $estado !== 'pendiente' ? User::where('role', 'admin')->inRandomOrder()->first()?->id : null,
                ]);
            }
        }

        $this->command->info('Documentos de aprendices creados exitosamente!');
    }

    private function generarTitulo($numero, $fase)
    {
        $titulos = [
            'Informe de Avance del Proyecto',
            'Análisis de Mercado',
            'Cronograma de Actividades',
            'Presupuesto Detallado',
            'Evaluación de Riesgos',
            'Plan de Marketing',
            'Estudio de Factibilidad',
            'Manual de Procedimientos',
            'Reglamento Interno',
            'Contrato de Servicios'
        ];

        return $titulos[($numero - 1) % count($titulos)] . ' - Fase ' . $fase;
    }

    private function generarDescripcion($numero, $fase)
    {
        $descripciones = [
            'Documento que presenta el progreso alcanzado en la implementación del proyecto productivo.',
            'Análisis completo del mercado objetivo incluyendo competencia y oportunidades.',
            'Planificación detallada de las actividades a desarrollar durante la fase.',
            'Desglose completo de los costos y recursos necesarios para la ejecución.',
            'Identificación y evaluación de los riesgos potenciales del proyecto.',
            'Estrategia de comercialización y posicionamiento en el mercado.',
            'Estudio que determina la viabilidad técnica y económica del proyecto.',
            'Guía paso a paso para la ejecución de las actividades del proyecto.',
            'Normas y procedimientos que rigen el funcionamiento de la unidad productiva.',
            'Acuerdo formal que establece los términos de colaboración con proveedores.'
        ];

        return $descripciones[($numero - 1) % count($descripciones)];
    }
}
