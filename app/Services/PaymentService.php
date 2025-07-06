<?php

namespace App\Services;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentService extends Controller
{
    /**
     * @param int $id
     * @return Payment|Model|null
     */
    public function getById(int $id): Model|Payment|null
    {
        return Payment::query()->find($id);
    }

    /**
     * @param int $id
     * @param $status
     * @return bool
     */
    public function updateStatus(int $id, $status): bool
    {
        DB::beginTransaction();

        try {
            $payment = $this->getById($id);
            $payment->status = $status;
            $isSuccessful = $payment->save();
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e->getTraceAsString());
            $isSuccessful = false;
        } finally {
            return $isSuccessful;
        }
    }
}
