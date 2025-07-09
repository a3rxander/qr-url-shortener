<?php

namespace App\Jobs;

use App\Models\ShortUrl; 
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class CleanupExpiredUrlsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 300; // 5 minutos
    public $tries = 3;

    private int $daysToKeepInactive;
    private int $daysToKeepUnused;

    public function __construct(
        int $daysToKeepInactive = 365,
        int $daysToKeepUnused = 30
    ) {
        $this->daysToKeepInactive = $daysToKeepInactive;
        $this->daysToKeepUnused = $daysToKeepUnused;
    }

    public function handle(): void
    {
        Log::info('Starting cleanup of expired URLs and QR codes');

        $this->cleanupInactiveShortUrls();
        $this->cleanupUnusedShortUrls(); 

        Log::info('Cleanup completed successfully');
    }

    private function cleanupInactiveShortUrls(): void
    {
        $cutoffDate = Carbon::now()->subDays($this->daysToKeepInactive);
        
        $expiredUrls = ShortUrl::where('is_active', false)
            ->where('updated_at', '<', $cutoffDate)
            ->get(); 

        Log::info("Cleaned up {$expiredUrls->count()} inactive short URLs");
    }

    private function cleanupUnusedShortUrls(): void
    {
        $cutoffDate = Carbon::now()->subDays($this->daysToKeepUnused);
        
        $unusedUrls = ShortUrl::where('click_count', 0)
            ->where('created_at', '<', $cutoffDate)
            ->get();
  
        Log::info("Cleaned up {$unusedUrls->count()} unused short URLs");
    }
 
    
}