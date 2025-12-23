<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('laundry_orders', function (Blueprint $table) {
            $table->string('payment_method')->nullable()->after('payment_status'); // cash, debit_card, qr, etc.
            $table->decimal('shipping', 10, 2)->default(0)->after('discount');
            $table->string('coupon_code')->nullable()->after('shipping');
            $table->decimal('coupon_discount', 10, 2)->default(0)->after('coupon_code');
            $table->decimal('order_tax_percent', 5, 2)->default(6)->after('tax'); // Store tax percentage used
        });
    }

    public function down(): void
    {
        Schema::table('laundry_orders', function (Blueprint $table) {
            $table->dropColumn(['payment_method', 'shipping', 'coupon_code', 'coupon_discount', 'order_tax_percent']);
        });
    }
};

