<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\ListadoMaestro;
use App\Models\User;

class DocumentoSubidoNotification extends Notification
{
    use Queueable;

    public $documento;

    /**
     * Create a new notification instance.
     */
    public function __construct(ListadoMaestro $documento)
    {
        $this->documento = $documento;
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
        return (new MailMessage)
            ->subject('📋 Nuevo Documento Pendiente de Revisión')
            ->greeting('Hola ' . $notifiable->name)
            ->line('Se ha subido un nuevo documento al listado maestro que requiere tu revisión.')
            ->line('**Documento:** ' . $this->documento->nombre_documento)
            ->line('**Proceso:** ' . $this->documento->nombre_proceso)
            ->line('**Responsable:** ' . $this->documento->responsable)
            ->action('Revisar Documento', route('superadmin.listado_maestro.index'))
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
            'titulo' => 'Nuevo Documento Pendiente',
            'mensaje' => 'Se ha subido un nuevo documento: ' . $this->documento->nombre_documento,
            'tipo' => 'documento_subido',
            'fecha' => now()->toDateTimeString(),
            'admin' => $this->documento->creador->name ?? 'Admin',
            'proceso' => $this->documento->nombre_proceso,
            'responsable' => $this->documento->responsable,
        ];
    }
}
