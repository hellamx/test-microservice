<?php

use App\Enums\PaymentStatusEnum;
use App\Models\Wallet;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('payment_id')->unique();
            $table->foreignIdFor(Wallet::class, 'wallet_id')
                ->constrained()
                ->onDelete('cascade');
            $table->decimal('amount', 20)->default(0);
            $table->string('project_name');
            $table->string('details');
            $table->string('status', 20)->default(strtolower(PaymentStatusEnum::UNPAID->name));
            $table->timestamps();

            $table->index('status', 'status_idx');
            $table->index('payment_id', 'payment_id_idx');
            $table->index('amount', 'amount_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
