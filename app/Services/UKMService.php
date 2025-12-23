<?php

namespace App\Services;

use App\Models\UKM;
use Illuminate\Support\Facades\Cache;

class UKMService
{
    /**
     * Get active UKMs with caching
     * Cache for 1 hour to reduce database load
     */
    public static function getActiveUKMs($columns = ['*'])
    {
        $cacheKey = 'active_ukms_' . md5(implode(',', $columns));

        return Cache::remember($cacheKey, 3600, function () use ($columns) {
            return UKM::where('verification_status', 'active')
                ->select($columns)
                ->orderBy('name')
                ->get();
        });
    }

    /**
     * Clear active UKMs cache
     * Call this when a UKM is created, updated, or deleted
     */
    public static function clearCache()
    {
        // Clear all cached active UKMs variations
        Cache::forget('active_ukms_*');
    }

    /**
     * Get UKM statistics
     */
    public static function getStatistics()
    {
        return Cache::remember('ukm_statistics', 3600, function () {
            return [
                'total' => UKM::count(),
                'active' => UKM::where('verification_status', 'active')->count(),
                'inactive' => UKM::where('verification_status', 'inactive')->count(),
                'with_events' => UKM::has('events')->count(),
            ];
        });
    }
}
