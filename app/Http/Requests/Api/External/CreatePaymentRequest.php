<?php

namespace App\Http\Requests\Api\External;

use App\Enums\CurrencyEnum;
use App\Enums\PaymentStatusEnum;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class CreatePaymentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {

        return [
            'data.payment_id' => [
                'required',
                'max:255',
                'string',
                Rule::unique('payments', 'payment_id'),
            ],
            'data.login' => [
                'required',
                'string',
                Rule::exists('users', 'login'),
            ],
            'data.project_name' => [
                'required',
                'string',
            ],
            'data.details' => [
                'required',
                'max:255',
            ],
            'data.amount' => [
                'required',
                'numeric',
                'min:0.01',
            ],
            'data.currency' => [
                'required',
                Rule::in(array_map(fn($case) => $case->name, CurrencyEnum::cases())),
            ],
            'data.status' => [
                'required',
                Rule::in(array_map(fn($case) => $case->name, PaymentStatusEnum::cases())),
            ],
        ];
    }

    /**
     * Return validation errors as json response.
     *
     * @param Validator $validator
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => false,
            'errors' => $validator->errors(),
        ], 403));
    }
}
