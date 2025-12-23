<?php

namespace App\Observers;

use App\Models\UKM;
use App\Services\UKMService;
use Illuminate\Support\Facades\Cache;

class UKMObserver
{
    /**
     * Handle the UKM "created" event.
     */
    public function created(UKM $ukm): void
    {
        $this->clearCaches();
    }

    /**
     * Handle the UKM "updated" event.
     */
    public function updated(UKM $ukm): void
    {
        $this->clearCaches();
    }

    /**
     * Handle the UKM "deleted" event.
     */
    public function deleted(UKM $ukm): void
    {
        $this->clearCaches();
    }

    /**
     * Clear all UKM-related caches
     */
    private function clearCaches(): void
    {
        UKMService::clearCache();
        Cache::forget('ukm_statistics');
    }
}
