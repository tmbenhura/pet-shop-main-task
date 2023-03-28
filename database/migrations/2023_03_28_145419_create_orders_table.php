<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->unsignedBigInteger('user_id');
            $table->uuid('order_status_uuid');
            $table->uuid('payment_uuid');
            $table->string('billing_address');
            $table->string('shipping_address');
            $table->bigInteger('delivery_fee_cents');
            $table->bigInteger('amount_cents');
            $table->timestamp('shipped_at')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');

            if (!config('database.default') === 'sqlite') {
                $table->foreign('order_status_uuid')->references('uuid')->on('order_statuses');
                $table->foreign('payment_uuid')->references('uuid')->on('payments');
            }

            $table->index(['uuid'], 'INX_ORDERS_UUID');
            $table->index(['uuid', 'user_id', 'order_status_uuid', 'payment_uuid'], 'INX_ORDERS_SELECTION');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
