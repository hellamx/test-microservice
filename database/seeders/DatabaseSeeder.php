<?php

namespace Database\Seeders;

use App\Enums\CurrencyEnum;
use App\Models\Payment;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Создаем тестовых пользователей
        /** @var Collection<int, User> $users */
        $users = User::factory(100)->create();

        // Создаем юзера для теста апи
        User::factory()->create([
            'login' => 'test',
            'project_name' => 'test'
        ]);

        // Создаем кошельки для каждого
        foreach($users as $user) {
            foreach (CurrencyEnum::cases() as $currency) {
                Wallet::factory(1)->create([
                    'user_id' => $user->id,
                    'currency' => $currency,
                ]);
            }
        }

        /** @var Collection<int, Wallet> $wallets */
        $wallets = Wallet::query()->get();

        // Создаем тестовые платежи
        Payment::factory(200)->create(function () use ($users, $wallets) {
            return [
                'project_name' => $users->random()->project_name,
                'wallet_id' => $wallets->random()->id,
            ];
        });
    }
}
