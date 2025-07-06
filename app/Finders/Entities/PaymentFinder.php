<?php

namespace App\Finders\Entities;

use App\Enums\CurrencyEnum;
use App\Enums\PaymentStatusEnum;
use App\Finders\AbstractFinder;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class PaymentFinder extends AbstractFinder
{
    /**
     * @return void
     */
    public function setModel(): void
    {
        $this->model = Payment::class;
    }

    /**
     * @param Request $request
     * @return void
     */
    protected function setSpecialConditions(Request $request): void
    {
        if (!empty($request->get('login'))) {
            $this->builder->whereHas('wallet.user', function (Builder $query) use ($request) {
                $query->where('login', 'like', '%' . $request->get('login') . '%');
            });
        }

        if (isset($request->all()['currency']) && !is_null($request->all()['currency'])) {
            $this->builder->whereHas('wallet', function (Builder $query) use ($request) {
                $query->where('currency', $request->get('currency'));
            });
        }

        if (!empty($request->get('status'))) {
            $this->builder->where('status', $request->get('status'));
        }

        if (!empty($request->get('amount'))) {
            $this->builder->where('amount', (float) $request->get('amount'));
        }

        if (!empty($request->get('payment_id'))) {
            $this->builder->where('payment_id', 'like', '%' . $request->get('payment_id') . '%');
        }

        if (!empty($request->get('details'))) {
            $this->builder->where('details', 'like', '%' . $request->get('details') . '%');
        }
    }

    /**
     * @return array
     */
    public function getMappedData(): array
    {
        return $this->getRows()->map(function (Payment $payment) {
            $wallet = $payment->wallet;
            $user = $wallet?->user;

            return [
                'id' => $payment->id,
                'payment_id' => $payment->payment_id,
                'login' => $user?->login,
                'details' => $payment->details,
                'project_name' => $payment->project_name,
                'amount' => $payment->amount,
                'status_value' => $payment->status,
                'status' => $payment->status === strtolower(PaymentStatusEnum::PAID->name) ? 'Оплачен' : 'Не оплачен',
                'currency' => mb_ucfirst(CurrencyEnum::tryFrom($wallet?->currency)?->name),
            ];
        })->toArray();
    }
}
