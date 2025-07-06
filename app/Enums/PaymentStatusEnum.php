<?php

namespace App\Enums;

enum PaymentStatusEnum: string
{
    case PAID = 'оплачен';
    case UNPAID = 'не оплачен';
}
