<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\ListadoMaestro;
use App\Notifications\DocumentoSubidoNotification;
use App\Notifications\DocumentoProcesadoNotification;
use Illuminate\Support\Facades\Notification;

class TestNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:notifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Probar el sistema de notificaciones del listado maestro';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('🚀 Iniciando prueba del sistema de notificaciones...');
        
        // Obtener usuarios
        $admin = User::where('role', 'admin')->first();
        $superadmin = User::where('role', 'superadmin')->first();
        
        if (!$admin || !$superadmin) {
            $this->error('❌ No se encontraron usuarios admin y superadmin');
            return 1;
        }
        
        $this->info("✅ Admin encontrado: {$admin->name}");
        $this->info("✅ Superadmin encontrado: {$superadmin->name}");
        
        // Crear un documento de prueba
        $documento = ListadoMaestro::create([
            'tipo_proceso' => 'Prueba',
            'nombre_proceso' => 'Proceso de Prueba Notificaciones',
            'subproceso_sig_subsistema' => 'Subsistema Prueba',
            'documentos' => 'documento_prueba.pdf',
            'numero_doc' => 'DOC-001',
            'responsable' => 'Responsable Prueba',
            'tipo_documento' => 'PDF',
            'nombre_documento' => 'Documento de Prueba para Notificaciones',
            'codigo' => 'COD-001',
            'version' => '1.0',
            'fecha_creacion' => now(),
            'estado' => 'pendiente',
            'creado_por' => $admin->id
        ]);
        
        $this->info("📄 Documento de prueba creado: ID {$documento->id}");
        
        // Enviar notificación de documento subido
        Notification::send([$superadmin], new DocumentoSubidoNotification($documento));
        $this->info("📬 Notificación de documento subido enviada al superadmin");
        
        // Aprobar el documento y enviar notificación de procesado
        $documento->update(['estado' => 'aprobado']);
        Notification::send([$admin], new DocumentoProcesadoNotification($documento, 'aprobado', $superadmin));
        $this->info("✅ Documento aprobado y notificación enviada al admin");
        
        // Verificar notificaciones
        $notifSuperadmin = $superadmin->notifications()->where('type', 'App\Notifications\DocumentoSubidoNotification')->count();
        $notifAdmin = $admin->notifications()->where('type', 'App\Notifications\DocumentoProcesadoNotification')->count();
        
        $this->info("📊 Notificaciones en base de datos:");
        $this->info("   - Superadmin: {$notifSuperadmin} notificaciones de documentos subidos");
        $this->info("   - Admin: {$notifAdmin} notificaciones de documentos procesados");
        
        $this->info("🎉 Prueba completada exitosamente!");
        $this->info("💡 Ahora puedes probar en el navegador:");
        $this->info("   - Ingresar como admin y verificar el icono de notificaciones");
        $this->info("   - Ingresar como superadmin y verificar el icono de notificaciones");
        
        return 0;
    }
}
