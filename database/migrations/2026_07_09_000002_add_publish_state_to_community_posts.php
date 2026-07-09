<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('community_posts', function (Blueprint $table) {
            $table->boolean('is_published')->default(true)->after('content');
            $table->timestamp('published_at')->nullable()->after('is_published');
            $table->index(['is_published', 'published_at']);
        });

        DB::table('community_posts')
            ->whereNull('published_at')
            ->update(['published_at' => DB::raw('created_at')]);
    }

    public function down(): void
    {
        Schema::table('community_posts', function (Blueprint $table) {
            $table->dropIndex(['is_published', 'published_at']);
            $table->dropColumn(['is_published', 'published_at']);
        });
    }
};
