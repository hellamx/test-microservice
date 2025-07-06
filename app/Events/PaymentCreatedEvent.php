<?php

namespace App\Events;

use App\Dto\Api\PaymentDto;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PaymentCreatedEvent
{
    use Dispatchable, SerializesModels;

    /**
     * @param PaymentDto $paymentDto
     */
    public function __construct(public readonly PaymentDto $paymentDto)
    {
        //
    }
}
