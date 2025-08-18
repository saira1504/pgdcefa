<?php

namespace App\Console\Commands;

use App\Models\DocumentoAprendiz;
use App\Models\Phase;
use App\Models\UnidadProductiva;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class SimularDocumentosAprendiz extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'simular:documentos-aprendiz {--count=5 : Cantidad de documentos a crear} {--estado=pendiente : Estado inicial de los documentos}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Simula la subida de documentos por parte de aprendices para pruebas';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $count = (int) $this->option('count');
        $estado = $this->option('estado');

        $this->info("Creando {$count} documentos de aprendices con estado '{$estado}'...");

        // Verificar que existan aprendices y unidades
        $aprendices = User::where('role', 'aprendiz')->get();
        $unidades = UnidadProductiva::all();
        $fases = Phase::all();

        if ($aprendices->isEmpty()) {
            $this->error('No hay aprendices en el sistema. Ejecuta primero UserSeeder.');
            return 1;
        }

        if ($unidades->isEmpty()) {
            $this->error('No hay unidades productivas en el sistema. Ejecuta primero UnidadProductivaSeeder.');
            return 1;
        }

        if ($fases->isEmpty()) {
            $this->error('No hay fases en el sistema. Ejecuta primero PhaseSeeder.');
            return 1;
        }

        $bar = $this->output->createProgressBar($count);
        $bar->start();

        for ($i = 0; $i < $count; $i++) {
            $aprendiz = $aprendices->random();
            $unidad = $unidades->random();
            $fase = $fases->random();

            // Asignar aprendiz a unidad si no está asignado
            if (!$aprendiz->unidadAsignada()->exists()) {
                $aprendiz->aprendizUnidad()->syncWithoutDetaching([
                    $unidad->id => ['fecha_asignacion' => now(), 'activo' => true]
                ]);
            }

            // Crear directorio para el aprendiz si no existe
            $directorio = "documentos/aprendiz/{$aprendiz->id}";
            if (!Storage::disk('public')->exists($directorio)) {
                Storage::disk('public')->makeDirectory($directorio);
            }

            // Crear documento
            $documento = DocumentoAprendiz::create([
                'aprendiz_id' => $aprendiz->id,
                'unidad_id' => $unidad->id,
                'tipo_documento_id' => $fase->id,
                'titulo' => 'Documento de Prueba ' . ($i + 1) . ' - Fase ' . $fase->numero,
                'descripcion' => 'Este es un documento de prueba generado automáticamente para la Fase ' . $fase->numero,
                'archivo_path' => $directorio . '/documento_prueba_' . ($i + 1) . '.pdf',
                'archivo_original' => 'documento_prueba_' . ($i + 1) . '.pdf',
                'mime_type' => 'application/pdf',
                'tamaño_archivo' => rand(100000, 2000000), // 100KB a 2MB
                'estado' => $estado,
                'fecha_subida' => now()->subDays(rand(0, 7)),
            ]);

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();

        $this->info("✅ Se crearon {$count} documentos de aprendices exitosamente!");
        $this->info("📁 Los documentos se crearon con estado: {$estado}");
        $this->info("🔍 Puedes verlos en: /admin/documentos o /superadmin/documentos");

        // Mostrar estadísticas
        $totalPendientes = DocumentoAprendiz::where('estado', 'pendiente')->count();
        $totalEnRevision = DocumentoAprendiz::where('estado', 'en_revision')->count();
        $totalAprobados = DocumentoAprendiz::where('estado', 'aprobado')->count();
        $totalRechazados = DocumentoAprendiz::where('estado', 'rechazado')->count();

        $this->newLine();
        $this->info("📊 Estadísticas actuales:");
        $this->table(
            ['Estado', 'Cantidad'],
            [
                ['Pendientes', $totalPendientes],
                ['En Revisión', $totalEnRevision],
                ['Aprobados', $totalAprobados],
                ['Rechazados', $totalRechazados],
            ]
        );

        return 0;
    }
}
