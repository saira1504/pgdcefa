<?php

namespace App\Console\Commands;

use App\Models\DocumentoAprendiz;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CrearArchivosPrueba extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crear:archivos-prueba';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crea archivos PDF de prueba para los documentos existentes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Creando archivos PDF de prueba para los documentos existentes...');

        $documentos = DocumentoAprendiz::all();
        
        if ($documentos->isEmpty()) {
            $this->error('No hay documentos en el sistema. Ejecuta primero simular:documentos-aprendiz');
            return 1;
        }

        $bar = $this->output->createProgressBar($documentos->count());
        $bar->start();

        foreach ($documentos as $documento) {
            // Crear directorio si no existe
            $directorio = dirname($documento->archivo_path);
            if (!Storage::disk('public')->exists($directorio)) {
                Storage::disk('public')->makeDirectory($directorio);
            }

            // Crear contenido HTML básico para el PDF
            $contenidoHTML = $this->generarContenidoHTML($documento);
            
            // Crear archivo HTML temporal
            $archivoHTML = storage_path('app/temp_' . $documento->id . '.html');
            file_put_contents($archivoHTML, $contenidoHTML);

            // Convertir HTML a PDF usando wkhtmltopdf si está disponible, o crear un archivo HTML
            if ($this->wkhtmltopdfDisponible()) {
                $this->convertirHTMLaPDF($archivoHTML, $documento);
            } else {
                // Si no hay wkhtmltopdf, crear un archivo HTML como alternativa
                $this->crearArchivoHTML($documento, $contenidoHTML);
            }

            // Limpiar archivo temporal
            if (file_exists($archivoHTML)) {
                unlink($archivoHTML);
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();

        $this->info('✅ Se crearon archivos de prueba para ' . $documentos->count() . ' documentos!');
        $this->info('📁 Los archivos se encuentran en: storage/app/public/documentos/');
        
        if (!$this->wkhtmltopdfDisponible()) {
            $this->warn('⚠️  wkhtmltopdf no está disponible. Se crearon archivos HTML en su lugar.');
            $this->info('💡 Para generar PDFs reales, instala wkhtmltopdf o usa una librería como Dompdf.');
        }

        return 0;
    }

    private function generarContenidoHTML($documento)
    {
        return '
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>' . htmlspecialchars($documento->titulo) . '</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 40px; line-height: 1.6; }
                .header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 20px; margin-bottom: 30px; }
                .info { background: #f5f5f5; padding: 20px; border-radius: 5px; margin: 20px 0; }
                .content { margin: 30px 0; }
                .footer { text-align: center; margin-top: 50px; padding-top: 20px; border-top: 1px solid #ccc; color: #666; }
            </style>
        </head>
        <body>
            <div class="header">
                <h1>' . htmlspecialchars($documento->titulo) . '</h1>
                <p><strong>Documento de Prueba - Sistema SENA</strong></p>
            </div>
            
            <div class="info">
                <h3>Información del Documento</h3>
                <p><strong>Aprendiz:</strong> ' . htmlspecialchars($documento->aprendiz->name) . '</p>
                <p><strong>Unidad Productiva:</strong> ' . htmlspecialchars($documento->unidad->nombre) . '</p>
                <p><strong>Tipo:</strong> ' . htmlspecialchars($documento->tipoDocumento->nombre ?? 'N/A') . '</p>
                <p><strong>Estado:</strong> ' . htmlspecialchars(ucfirst(str_replace('_', ' ', $documento->estado))) . '</p>
                <p><strong>Fecha de Subida:</strong> ' . $documento->fecha_subida->format('d/m/Y H:i') . '</p>
            </div>
            
            <div class="content">
                <h3>Descripción</h3>
                <p>' . htmlspecialchars($documento->descripcion ?: 'Este es un documento de prueba generado automáticamente para demostrar la funcionalidad del sistema de gestión documental del SENA.') . '</p>
                
                <h3>Contenido del Documento</h3>
                <p>Este es un documento de prueba que simula el contenido real que un aprendiz podría subir al sistema. El documento incluye:</p>
                <ul>
                    <li>Información básica del aprendiz</li>
                    <li>Detalles de la unidad productiva</li>
                    <li>Estado actual del documento</li>
                    <li>Fecha y hora de subida</li>
                    <li>Descripción del contenido</li>
                </ul>
                
                <p>Este archivo fue generado automáticamente para probar las funcionalidades de:</p>
                <ul>
                    <li>Descarga de documentos</li>
                    <li>Vista previa de archivos</li>
                    <li>Gestión de estados</li>
                    <li>Sistema de revisión</li>
                </ul>
            </div>
            
            <div class="footer">
                <p><strong>Sistema de Gestión Documental SENA</strong></p>
                <p>Documento generado el: ' . now()->format('d/m/Y H:i:s') . '</p>
                <p>Este es un archivo de prueba - No contiene información real</p>
            </div>
        </body>
        </html>';
    }

    private function wkhtmltopdfDisponible()
    {
        $output = shell_exec('which wkhtmltopdf 2>&1');
        return !empty($output) && strpos($output, 'wkhtmltopdf') !== false;
    }

    private function convertirHTMLaPDF($archivoHTML, $documento)
    {
        $archivoPDF = storage_path('app/public/' . $documento->archivo_path);
        
        // Crear directorio si no existe
        $directorio = dirname($archivoPDF);
        if (!is_dir($directorio)) {
            mkdir($directorio, 0755, true);
        }

        // Comando para convertir HTML a PDF
        $comando = "wkhtmltopdf --page-size A4 --margin-top 20 --margin-right 20 --margin-bottom 20 --margin-left 20 --encoding UTF-8 \"$archivoHTML\" \"$archivoPDF\" 2>&1";
        
        $output = shell_exec($comando);
        
        if (file_exists($archivoPDF)) {
            $this->line("✅ PDF creado: " . basename($archivoPDF));
        } else {
            $this->warn("⚠️  No se pudo crear PDF: " . basename($archivoPDF));
            $this->line("Output: " . $output);
        }
    }

    private function crearArchivoHTML($documento, $contenidoHTML)
    {
        // Cambiar la extensión a .html
        $archivoHTML = str_replace('.pdf', '.html', $documento->archivo_path);
        $rutaCompleta = storage_path('app/public/' . $archivoHTML);
        
        // Crear directorio si no existe
        $directorio = dirname($rutaCompleta);
        if (!is_dir($directorio)) {
            mkdir($directorio, 0755, true);
        }

        // Guardar archivo HTML
        file_put_contents($rutaCompleta, $contenidoHTML);
        
        // Actualizar el modelo para reflejar el cambio de extensión
        $documento->update([
            'archivo_path' => $archivoHTML,
            'archivo_original' => str_replace('.pdf', '.html', $documento->archivo_original),
            'mime_type' => 'text/html'
        ]);
        
        $this->line("✅ HTML creado: " . basename($archivoHTML));
    }
}
