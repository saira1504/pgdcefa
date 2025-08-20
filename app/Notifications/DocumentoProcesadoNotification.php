<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\ListadoMaestro;
use App\Models\User;

class DocumentoProcesadoNotification extends Notification
{
    use Queueable;

    public $documento;
    public $accion;
    public $superadmin;

    /**
     * Create a new notification instance.
     */
    public function __construct(ListadoMaestro $documento, string $accion, User $superadmin)
    {
        $this->documento = $documento;
        $this->accion = $accion;
        $this->superadmin = $superadmin;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database']; // Solo base de datos por ahora
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $accionTexto = $this->accion === 'aprobado' ? 'aprobado' : 'rechazado';
        $colorAccion = $this->accion === 'aprobado' ? 'success' : 'danger';
        
        return (new MailMessage)
            ->subject('📋 Documento ' . ucfirst($accionTexto) . ' - Listado Maestro')
            ->greeting('Hola ' . $notifiable->name)
            ->line('Tu documento del listado maestro ha sido ' . $accionTexto . ' por el super administrador.')
            ->line('**Documento:** ' . $this->documento->nombre_documento)
            ->line('**Proceso:** ' . $this->documento->nombre_proceso)
            ->line('**Responsable:** ' . $this->documento->responsable)
            ->line('**Super Admin:** ' . $this->superadmin->name)
            ->line('**Fecha:** ' . now()->format('d/m/Y H:i'))
            ->action('Ver Documento', route('admin.listado_maestro'))
            ->line('Gracias por usar nuestra aplicación.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'documento_id' => $this->documento->id,
            'titulo' => 'Documento ' . ucfirst($this->accion),
            'mensaje' => 'Tu documento "' . $this->documento->nombre_documento . '" ha sido ' . $this->accion,
            'tipo' => 'documento_' . $this->accion,
            'accion' => $this->accion,
            'superadmin' => $this->superadmin->name,
            'fecha' => now()->toDateTimeString(),
        ];
    }
}
