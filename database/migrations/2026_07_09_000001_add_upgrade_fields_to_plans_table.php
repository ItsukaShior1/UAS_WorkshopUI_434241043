<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            // Diskon otomatis yang diberikan jika user upgrade dari paket Bookify App ke paket Marketplace
            $table->unsignedTinyInteger('upgrade_discount_percent')->default(0)->after('includes_marketplace');
        });
    }

    public function down(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->dropColumn('upgrade_discount_percent');
        });
    }
};