<?php

namespace App\Events;

use App\Models\Coin;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AggregatePriceChange implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels, Dispatchable;
    /**
     * @var Coin
     */
    private $coin;

    /**
     * @var float
     */
    private $price;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Coin $coin, $price)
    {
        $this->coin = $coin;
        $this->price = $price;
    }

    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith()
    {
        return ['price' => $this->price];
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('AggregatePriceChange.' . $this->coin->id);
    }
}
