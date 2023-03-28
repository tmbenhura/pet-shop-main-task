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
        Schema::create('order_statuses', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->string('title')->unique();
            $table->timestamps();

            $table->index(['uuid'], 'INX_ORDER_STATUSES_UUID');
            $table->index(['uuid', 'title'], 'INX_ORDER_STATUSES_SELECTION');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_statuses');
    }
};
