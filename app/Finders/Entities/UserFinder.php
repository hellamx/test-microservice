<?php

namespace App\Finders\Entities;

use App\Enums\CurrencyEnum;
use App\Finders\AbstractFinder;
use App\Models\User;
use Illuminate\Http\Request;

class UserFinder extends AbstractFinder
{
    /**
     * @return void
     */
    public function setModel(): void
    {
        $this->model = User::class;
    }

    /**
     * @param Request $request
     * @return void
     */
    protected function setSpecialConditions(Request $request): void
    {
        if (!empty($request->get('login'))) {
            $this->builder->where('login', 'like', '%' . $request->get('login') . '%');
        }
    }

    /**
     * @return array
     */
    public function getMappedData(): array
    {
        return $this->getRows()->map(function (User $user) {
            return [
                'id' => $user->id,
                'login' => $user->login,
                'project_name' => $user->project_name,
                'wallets' => collect(CurrencyEnum::cases())->map(function ($currency) use ($user) {
                    $wallet = $user->wallets->firstWhere('currency', $currency);

                    return [
                        'currency' => CurrencyEnum::cases()[$currency->value],
                        'balance' => $wallet ? $wallet->balance : 0.00,
                    ];
                })->toArray(),
            ];
        })->toArray();
    }
}
