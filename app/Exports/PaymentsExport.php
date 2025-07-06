<?php

namespace App\Exports;

use App\Enums\CurrencyEnum;
use App\Enums\PaymentStatusEnum;
use App\Models\Payment;
use Generator;
use Maatwebsite\Excel\Concerns\FromGenerator;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PaymentsExport implements FromGenerator, WithHeadings
{
    /**
     * @return Generator
     */
    public function generator(): Generator
    {
        /** @var Payment $payment */
        foreach (Payment::with(['wallet.user'])->cursor() as $payment) {
            yield [
                $payment->id,
                $payment->payment_id,
                $payment->wallet?->user?->login,
                $payment->project_name,
                $payment->details,
                $payment->amount,
                (CurrencyEnum::tryFrom($payment->wallet?->currency))->name,
                (PaymentStatusEnum::{strtoupper($payment->status)})->value,
                $payment->created_at->toDateTimeString(),
            ];
        }
    }

    /**
     * @return string[]
     */
    public function headings(): array
    {
        return [
            'ID',
            'ID платежа',
            'Логин',
            'Название проекта',
            'Реквизиты',
            'Сумма',
            'Валюта',
            'Статус',
            'Дата создания',
        ];
    }
}

