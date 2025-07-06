<?php

namespace App\Jobs;

use App\Dto\Api\PaymentDto;
use App\Enums\CurrencyEnum;
use App\Enums\PaymentStatusEnum;
use App\Models\Payment;
use App\Models\User;
use App\Models\Wallet;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CreatePaymentJob implements ShouldQueue
{
    use Queueable;

    /**
     * @var PaymentDto
     */
    private PaymentDto $paymentDto;

    /**
     * Create a new job instance.
     */
    public function __construct(PaymentDto $paymentDto)
    {
        $this->paymentDto = $paymentDto;
    }

    /**
     * Execute the job.
     * @throws Exception
     */
    public function handle(): void
    {
        DB::beginTransaction();

        try {
            $user = User::query()->where('login', $this->paymentDto->login)->firstOrFail();

            $currency = CurrencyEnum::{strtoupper($this->paymentDto->currency)}->value;
            $wallet = Wallet::query()
                ->where('currency', $currency)
                ->where('user_id', $user->id)
                ->lock(
                    DB::raw('FOR UPDATE SKIP LOCKED')
                )
                ->first();

            if ($wallet === null) {
                $wallet = Wallet::query()->create([
                    'user_id' => $user->id,
                    'currency' => $currency,
                ]);
            }

            Payment::query()->create([
                'payment_id' => $this->paymentDto->payment_id,
                'wallet_id' => $wallet->id,
                'amount' => $this->paymentDto->amount,
                'project_name' => $user->project_name,
                'details' => $this->paymentDto->details,
                'currency' => $this->paymentDto->currency,
                'status' => $this->paymentDto->status,
            ]);

            if ($this->paymentDto->status === PaymentStatusEnum::PAID->name) {
                $wallet->balance += $this->paymentDto->amount;
                $wallet->save();
            }

            DB::commit();
        } catch (Exception|ModelNotFoundException $e) {
            DB::rollBack();
            Log::error($e->getTraceAsString());
            throw $e;
        }
    }
}
