<?php

namespace App\Listeners;

use App\Events\ResellerOrderActivated;
use App\Mail\ResellerCredentialsMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendResellerCredentials
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ResellerOrderActivated $event): void
    {
        $order = $event->order;
        $user = $order->user;

        // Only send credentials if this is a reseller plan
        if ($order->pricingPlan->plan_type === 'reseller') {
            Mail::to($user->email)->send(new ResellerCredentialsMail($order, $user));
        }
    }
}
