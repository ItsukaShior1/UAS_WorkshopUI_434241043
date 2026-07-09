<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('user')->after('business_type');
            $table->boolean('is_active')->default(true)->after('role');
            $table->string('deactivated_reason')->nullable()->after('is_active');
        });

        DB::table('users')
            ->whereNull('role')
            ->update(['role' => 'user', 'is_active' => true]);
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['deactivated_reason', 'is_active', 'role']);
        });
    }
};
