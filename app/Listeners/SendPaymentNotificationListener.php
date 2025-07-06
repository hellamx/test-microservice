<?php

namespace App\Listeners;

use App\Events\PaymentCreatedEvent;
use App\Jobs\SendPaymentNotificationJob;

class SendPaymentNotificationListener
{
    /**
     * @param PaymentCreatedEvent $event
     * @return void
     */
    public function handle(PaymentCreatedEvent $event): void
    {
        SendPaymentNotificationJob::dispatchAfterResponse(
            $event->paymentDto
        );
    }
}
