<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SaludoUsuario
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    use SerializesModels;

    public $mensaje;

    public function __construct($nombre)
    {
        $this->mensaje = "¡Hola, $nombre! Bienvenido a nuestra aplicación.";
    }

    public function broadcastOn()
    {
        // Especifica el canal al que deseas enviar el saludo
        return new Channel('saludos');
    }
}
