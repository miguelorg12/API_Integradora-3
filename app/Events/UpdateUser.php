<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UpdateUser implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user_id;
    public $user_status;
    public $user_rol;
    public function __construct(int $user_id, bool $user_status, int $user_rol)
    {
        $this->user_id = $user_id;
        $this->user_status = $user_status;
        $this->user_rol = $user_rol;
    }


    public function broadcastOn()
    {
        return new Channel('UserUpdate');
    }

    public function broadcastAs()
    {
        return 'UpdateUser';
    }
}
