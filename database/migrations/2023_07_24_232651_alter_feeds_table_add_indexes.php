<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('feeds', function (Blueprint $table) {
            $table->string('link', 1000)->change();
        });
    }

    public function down(): void
    {
        Schema::table('feeds', function (Blueprint $table) {
            $table->text('link')->change();
        });
    }
};
