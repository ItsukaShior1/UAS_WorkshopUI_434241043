<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Pembeda jenis transaksi:
            // - subscription_payment: pembayaran paket langganan (dikelola admin)
            // - bookkeeping: pencatatan income/expense oleh user (tidak ditampilkan di admin Transaksi)
            $table->string('kind', 30)->default('bookkeeping')->after('type')->index();
        });

        // Backfill: transaksi yang memiliki category 'Langganan' tandai sebagai subscription_payment
        \Illuminate\Support\Facades\DB::table('transactions')
            ->where('category', 'Langganan')
            ->update(['kind' => 'subscription_payment']);
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('kind');
        });
    }
};