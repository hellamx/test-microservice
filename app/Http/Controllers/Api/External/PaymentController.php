<?php

namespace App\Http\Controllers\Api\External;

use App\Dto\Api\PaymentDto;
use App\Events\PaymentCreatedEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\External\CreatePaymentRequest;
use App\Jobs\CreatePaymentJob;
use Illuminate\Http\JsonResponse;

class PaymentController extends Controller
{
    /**
     * @param CreatePaymentRequest $request
     * @return JsonResponse
     */
    public function create(CreatePaymentRequest $request): JsonResponse
    {
        $paymentDto = PaymentDto::from($request->input('data'));

        CreatePaymentJob::dispatchAfterResponse($paymentDto);

        event(
            new PaymentCreatedEvent($paymentDto)
        );

        return response()->json([
            'status' => true,
            'message' => 'queued',
        ], 201);
    }
}
