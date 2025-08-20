<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Marcar una notificación como leída
     */
    public function markAsRead($id)
    {
        try {
            $notification = Auth::user()->notifications()->findOrFail($id);
            $notification->markAsRead();
            
            return response()->json([
                'success' => true,
                'message' => 'Notificación marcada como leída'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al marcar la notificación'
            ], 500);
        }
    }

    /**
     * Marcar todas las notificaciones como leídas
     */
    public function markAllAsRead()
    {
        try {
            Auth::user()->unreadNotifications()
                ->where('type', 'App\Notifications\DocumentoProcesadoNotification')
                ->update(['read_at' => now()]);
            
            return response()->json([
                'success' => true,
                'message' => 'Todas las notificaciones han sido marcadas como leídas'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al marcar las notificaciones'
            ], 500);
        }
    }

    /**
     * Obtener notificaciones del usuario
     */
    public function getNotifications()
    {
        try {
            $notifications = Auth::user()
                ->notifications()
                ->where('type', 'App\Notifications\DocumentoProcesadoNotification')
                ->orderBy('created_at', 'desc')
                ->get();
            
            return response()->json([
                'success' => true,
                'notifications' => $notifications
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener las notificaciones'
            ], 500);
        }
    }
}
