<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Order
 *
 * @method static \Database\Factories\OrderFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Order newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Order newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Order query()
 * @property int $id
 * @property string $uuid
 * @property int|null $user_id
 * @property string $order_status_uuid
 * @property string $payment_uuid
 * @property string $billing_address
 * @property string $shipping_address
 * @property int $delivery_fee_cents
 * @property int $amount_cents
 * @property string|null $shipped_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereAmountCents($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereBillingAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereDeliveryFeeCents($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereOrderStatusUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order wherePaymentUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereShippedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereShippingAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereUuid($value)
 * @mixin \Eloquent
 */
class Order extends Model
{
    use HasUuid;
    use HasFactory;

    /**
     * Order user
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Order status
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(OrderStatus::class, 'order_status_uuid', 'uuid');
    }
}
