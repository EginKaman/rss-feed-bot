<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSitesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sites', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->string('title');
            $table->text('description')->nullable();
            $table->text('home_link');
            $table->text('link');
            $table->timestamp('fed_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
        Schema::create('user_site', function (Blueprint $table) {
            $table->foreignUuid('user_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignUuid('site_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();

            $table->primary(['user_id', 'site_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_site');
        Schema::dropIfExists('sites');
    }
}
