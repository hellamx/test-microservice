<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $payment_id
 * @property int $wallet_id
 * @property float $amount
 * @property string $project_name
 * @property string $details
 * @property string $status
 * @property Wallet $wallet
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class Payment extends Model
{
    /**
     * @use HasFactory<UserFactory>
     */
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'payments';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'payment_id',
        'wallet_id',
        'amount',
        'project_name',
        'details',
        'status',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'payment_id' => 'string',
        'wallet_id' => 'int',
        'amount' => 'float',
        'project_name' => 'string',
        'details' => 'string',
    ];

    /**
     * @return BelongsTo
     */
    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class);
    }
}
