<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Pemilik transaksi (nullable agar transaksi lama tanpa user tidak hilang)
            $table->foreignId('user_id')->nullable()->after('id')->constrained()->nullOnDelete();

            // Status tambahan untuk mendukung alur refund / pembatalan oleh admin
            $table->string('status', 20)->default('completed')->after('type')->index();
            $table->timestamp('cancelled_at')->nullable()->after('notes');
            $table->foreignId('cancelled_by')->nullable()->after('cancelled_at')->constrained('users')->nullOnDelete();
            $table->string('cancellation_reason', 500)->nullable()->after('cancelled_by');
            $table->string('refund_reference', 100)->nullable()->after('cancellation_reason');
            $table->unsignedBigInteger('refund_amount')->nullable()->after('refund_reference');
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['cancelled_by']);
            $table->dropColumn([
                'user_id',
                'status',
                'cancelled_at',
                'cancelled_by',
                'cancellation_reason',
                'refund_reference',
                'refund_amount',
            ]);
        });
    }
};