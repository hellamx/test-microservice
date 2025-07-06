<?php

namespace App\Dto\Api;

use App\Dto\AbstractDto;

class PaymentDto extends AbstractDto
{
    /**
     * @param string $payment_id
     * @param string $login
     * @param string $project_name
     * @param string $details
     * @param float $amount
     * @param string $currency
     * @param string $status
     */
    public function __construct(
        public string $payment_id,
        public string $login,
        public string $project_name,
        public string $details,
        public float $amount,
        public string $currency,
        public string $status
    )
    {}
}
