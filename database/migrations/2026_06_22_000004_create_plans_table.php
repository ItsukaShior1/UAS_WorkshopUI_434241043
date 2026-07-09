<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50);
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('billing_period', ['monthly', 'yearly']);
            $table->decimal('price', 12, 2);
            $table->boolean('includes_marketplace')->default(false);
            $table->json('features')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->unique(['code', 'billing_period']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
