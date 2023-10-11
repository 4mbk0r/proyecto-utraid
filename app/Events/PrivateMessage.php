<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class PrivateMessage implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    private $user;
    public function __construct(User $user)
    {
        //
        $this->user = $user;
    }

    /**d
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('message-channel.'.$this->user->id);
    }
    public function broadcastAs(){
        return 'PrivateEvent';
    }
    public function broadcastWith(){
        $username = $this->user->username;

        // Obtener la fecha actual en formato 'Y-m-d'
        $hoy = Carbon::now()->toDateString();

        $resultados = DB::table('atenders')
            ->select(
                'atenders.*',
                'fichas.*',
                'horarios.*',
                'salas.*',
                'dar_citas.*',
                'personas.*',
                'equipos.*',
                'asignar_equipos.*',
            )
            ->leftJoin('fichas', 'fichas.id', '=', 'atenders.id_ficha')
            ->leftJoin('salas', 'salas.id', '=', 'fichas.id_sala')
            ->leftJoin('horarios', 'horarios.id', '=', 'fichas.id_horario')
            ->leftJoin('dar_citas', 'dar_citas.id_ficha', '=', 'fichas.id')
            ->leftJoin('personas', 'personas.id', '=', 'dar_citas.id_persona')
            ->leftJoin('equipos', 'equipos.id', '=', 'atenders.id_designado')
            ->leftJoin('asignar_equipos', 'asignar_equipos.id_equipo', '=', 'equipos.id')
            ->leftJoin('users', 'users.id', '=', 'asignar_equipos.id_usuario')
            ->where('users.username', $username)
            ->where('fichas.fecha', $hoy)
            ->get();

        
        return ['massage' => 'this notificacion is a private'.$this->user->id,
        'datos' => $resultados];
    }
}
