<?php

namespace App\Jobs;

use App\Dto\Api\PaymentDto;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Throwable;

class SendPaymentNotificationJob implements ShouldQueue
{
    use Dispatchable,
        InteractsWithQueue,
        Queueable,
        SerializesModels;

    /**
     * @var PaymentDto
     */
    protected PaymentDto $paymentDto;

    /**
     * Кол-во попыток
     *
     * @var int
     */
    public int $tries = 5;

    /**
     * Задержки между попытками
     *
     * @var int[]
     */
    public array $backoff = [10, 30, 60];

    public function __construct(PaymentDto $paymentDto)
    {
        $this->paymentDto = $paymentDto;
    }

    /**
     * @throws ConnectionException
     * @throws Exception
     */
    public function handle(): void
    {
        $response = Http::timeout(10)
            ->post(
                url: config('app.external_rest_api_resource'),
                data: $this->paymentDto->all()
            );

        // Сигнал о том, что событие доставлено (условно) - статус 2xx или 3xx, иначе - джоба провалена
        if ($response->status() < 200 || $response->status() >= 400) {
            throw new Exception('Failed send notification. Status: ' . $response->status());
        }
    }

    /**
     * @param Throwable $e
     * @return void
     */
    public function failed(Throwable $e): void
    {
        logger()->error(
            message: __CLASS__ . ' failed',
            context: [
                'payload' => $this->paymentDto->all(),
                'error' => $e->getMessage(),
            ]
        );
    }
}
