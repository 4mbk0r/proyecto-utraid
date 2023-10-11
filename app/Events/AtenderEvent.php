<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class AtenderEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    
     
    private $user;
    public $id;
    private $fecha;
 
     /**
      * Create a new event instance.
      *
      * @param int $id
      * @param string $fecha
      * @return void
      */
    public function __construct($user, $fecha)
    {
        $this->user = $user;
        $this->fecha = $fecha;
    }
    public function broadcastAs(){
        return 'AtenderEvent';
    }
    public function broadcastOn()
    {
        return new PrivateChannel('atencion.'.$this->user->id.".".$this->fecha);
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
            ->where('users.username', $this->user->username)
            ->where('fichas.fecha', $this->fecha)
            ->get();

        
        return ['massage' => 'this notificacion is a private'.$this->user->id,
        'datos' => $resultados];
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    
}
