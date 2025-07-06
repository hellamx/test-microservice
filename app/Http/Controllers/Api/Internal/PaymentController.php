<?php

namespace App\Http\Controllers\Api\Internal;

use App\Exports\PaymentsExport;
use App\Finders\Entities\PaymentFinder;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Internal\UpdatePaymentStatusRequest;
use App\Services\PaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class PaymentController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function get(Request $request)
    {
        /** @var PaymentFinder $paymentFinder */
        $paymentFinder = app(PaymentFinder::class);
        $paymentFinder->setRelations(['wallet.user']);
        $payments = $paymentFinder->findByRequest($request);
        $paymentsData = $paymentFinder->getMappedData();

        return response()->json([
            'data' => $paymentsData,
            'total' => $payments->total(),
            'current_page' => $payments->currentPage(),
            'last_page' => $payments->lastPage(),
        ]);
    }

    /**
     * @param UpdatePaymentStatusRequest $request
     * @return JsonResponse
     */
    public function updateStatus(UpdatePaymentStatusRequest $request): JsonResponse
    {
        /** @var PaymentService $paymentService */
        $paymentService = app(PaymentService::class);

        return response()->json([
            'status' => $paymentService->updateStatus(
                id: $request->id,
                status: $request->status
            )
        ]);
    }

    /**
     * @return BinaryFileResponse
     */
    public function export(): BinaryFileResponse
    {
        return Excel::download(new PaymentsExport, 'payments.xlsx');
    }
}
