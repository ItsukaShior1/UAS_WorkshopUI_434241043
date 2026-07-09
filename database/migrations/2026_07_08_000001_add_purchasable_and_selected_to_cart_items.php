<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cart_items', function (Blueprint $table) {
            $table->string('purchasable_type')->nullable()->after('cart_id');
            $table->unsignedBigInteger('purchasable_id')->nullable()->after('purchasable_type');
            $table->boolean('selected')->default(true)->after('quantity');

            $table->index(['purchasable_type', 'purchasable_id']);
        });
    }

    public function down(): void
    {
        Schema::table('cart_items', function (Blueprint $table) {
            $table->dropIndex(['purchasable_type', 'purchasable_id']);
            $table->dropColumn(['purchasable_type', 'purchasable_id', 'selected']);
        });
    }
};
