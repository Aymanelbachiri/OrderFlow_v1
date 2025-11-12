<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Order;
use App\Models\PaymentIntent;

class PaymentCompleted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $order;
    public $paymentIntent;

    /**
     * Create a new event instance.
     */
    public function __construct(Order $order, PaymentIntent $paymentIntent)
    {
        $this->order = $order;
        $this->paymentIntent = $paymentIntent;
    }
}
