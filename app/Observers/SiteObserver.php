<?php

namespace App\Observers;

use App\Models\Site;

class SiteObserver
{
    public function created(Site $site): void
    {
    }

    public function updated(Site $site): void
    {
    }

    public function deleted(Site $site): void
    {
        $site->feeds()->delete();
    }

    public function restored(Site $site): void
    {
    }

    public function forceDeleted(Site $site): void
    {
        $site->feeds()->forceDelete();
    }
}
