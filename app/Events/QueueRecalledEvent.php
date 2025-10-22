<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class QueueRecalledEvent implements ShouldBroadcast // <-- Implementasikan ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $queue;
    public $counter;

    public function __construct($queue, $counter)
    {
        $this->queue = $queue;
        $this->counter = $counter;
    }

    /**
     * Dapatkan channel yang akan dibroadcast.
     * Kita gunakan channel publik agar monitor bisa mendengarkannya.
     */
    public function broadcastOn()
    {
        return new Channel('monitor-channel'); // Nama channel publik
    }

    /**
     * Nama event yang akan dikirim.
     */
    public function broadcastAs()
    {
        return 'queue.recalled'; // Nama event yang unik
    }

    /**
     * Data yang akan dikirim ke monitor.
     */
    public function broadcastWith()
    {
        return [
            'queue' => [
                'id' => $this->queue->id,
                'queue_number' => $this->queue->queue_number,
                'status' => $this->queue->status,
                'counter_id' => $this->queue->counter_id
            ],
            'counter' => [
                'id' => $this->counter->id,
                'nama_loket' => $this->counter->nama_loket
            ]
        ];
    }
}