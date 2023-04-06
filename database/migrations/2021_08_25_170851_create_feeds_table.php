<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeedsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('feeds', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->foreignUuid('site_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->text('photo')->nullable();
            $table->string('title')->nullable()->index();
            $table->text('link')->index();
            $table->text('description')->nullable();
            $table->timestamp('published_at')->index();
            $table->softDeletes();
            $table->timestamps();

            $table->unique([
                'site_id',
                'link',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feeds');
    }
}
