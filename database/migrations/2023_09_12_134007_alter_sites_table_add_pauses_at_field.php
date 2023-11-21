<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up(): void
    {
        Schema::table('sites', static function (Blueprint $table): void {
            $table->timestamp('pauses_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('sites', static function (Blueprint $table): void {
            $table->dropColumn(['pauses_at']);
        });
    }
};
