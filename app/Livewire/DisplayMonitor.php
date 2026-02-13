<?php

namespace App\Livewire;

use App\Models\Settings;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;

#[Layout('layouts.guest')]
class DisplayMonitor extends Component
{
    public $activeTellers = [];
    public $upNext = [];
    #[Locked]
    public $marqueeText = '';
    
    #[Locked]
    public $videoUrl = '';

    public function mount()
    {
        // Get marquee text from settings (fallback to env, then default)
        $this->marqueeText = Settings::get('display_marquee_text', 
            env('DISPLAY_MARQUEE_TEXT', 'Welcome to NORSU-GUIHULNGAN Queue System. Please wait for your number to be called.')
        );

        // Get video URL or file from settings
        $videoFile = Settings::get('display_video_file', null);
        
        if ($videoFile && Storage::disk('public')->exists($videoFile)) {
            // Use uploaded video file - return direct URL (not embed format)
            $this->videoUrl = Storage::disk('public')->url($videoFile);
        } else {
            // Use video URL from settings (fallback to env)
            $rawVideoUrl = Settings::get('display_video_url', env('DISPLAY_VIDEO_URL', ''));
            if (!empty($rawVideoUrl)) {
                $this->videoUrl = $this->convertToEmbedUrl($rawVideoUrl);
            } else {
                $this->videoUrl = '';
            }
        }
        
        $this->loadDisplay();
    }

    /**
     * Convert YouTube URL to embed format
     * Supports:
     * - https://www.youtube.com/watch?v=VIDEO_ID
     * - https://youtu.be/VIDEO_ID
     * - https://www.youtube.com/embed/VIDEO_ID (already embed format)
     */
    private function convertToEmbedUrl($url)
    {
        if (empty($url)) {
            return '';
        }

        // If already in embed format, return as is
        if (strpos($url, 'youtube.com/embed/') !== false) {
            return $url;
        }

        // Extract video ID from different YouTube URL formats
        $videoId = null;
        
        // Format: https://www.youtube.com/watch?v=VIDEO_ID
        if (preg_match('/youtube\.com\/watch\?v=([a-zA-Z0-9_-]+)/', $url, $matches)) {
            $videoId = $matches[1];
        }
        // Format: https://youtu.be/VIDEO_ID
        elseif (preg_match('/youtu\.be\/([a-zA-Z0-9_-]+)/', $url, $matches)) {
            $videoId = $matches[1];
        }
        // Format: https://www.youtube.com/embed/VIDEO_ID?params
        elseif (preg_match('/youtube\.com\/embed\/([a-zA-Z0-9_-]+)/', $url, $matches)) {
            $videoId = $matches[1];
        }

        if ($videoId) {
            // Return embed URL with autoplay, loop, and mute parameters
            // Using youtube-nocookie.com for privacy and fewer tracking attempts
            // Additional parameters to reduce tracking and errors
            return "https://www.youtube-nocookie.com/embed/{$videoId}?autoplay=1&loop=1&playlist={$videoId}&mute=1&controls=1&rel=0&modestbranding=1&playsinline=1&enablejsapi=0";
        }

        // If we can't parse it, return empty (will show placeholder)
        return '';
    }


    // Listen for Livewire event dispatched from JavaScript
    #[On('refresh-display')]
    public function handleRefreshDisplay()
    {
        // Clear cache to ensure fresh data on real-time updates
        $today = Ticket::today();
        cache()->forget("active_tellers_{$today}");
        $this->loadDisplay();
    }

    public function loadDisplay()
    {
        $today = Ticket::today();
        
        // Cache active tellers for 2 minutes to reduce database load
        // But bypass cache when refresh-display is called (handled in handleRefreshDisplay)
        $this->activeTellers = cache()->remember("active_tellers_{$today}", 120, function() use ($today) {
            // Get all active/online tellers (users with role 'teller' who have active sessions)
            // A teller is considered "active" if they have a session with last_activity within the last 30 minutes
            $activeThreshold = now()->subMinutes(30)->timestamp;
            
            $activeTellerIds = DB::table('sessions')
                ->where('user_id', '!=', null)
                ->where('last_activity', '>=', $activeThreshold)
                ->pluck('user_id')
                ->unique()
                ->toArray();
            
            if (empty($activeTellerIds)) {
                return [];
            }
            
            // Get all tellers who are active/online
            $tellers = User::where('role', 'teller')
                ->whereIn('id', $activeTellerIds)
                ->with('category:id,name,prefix')
                ->orderBy('counter_name', 'asc')
                ->get();
            
            // Get all serving tickets for these tellers in one query
            // Load category relationship so code accessor works properly
            $servingTickets = Ticket::forToday()
                ->serving()
                ->whereIn('teller_id', $activeTellerIds)
                ->with('category:id,prefix,name')
                ->orderBy('updated_at', 'desc')
                ->get()
                ->groupBy('teller_id');
            
            // Map tellers with their serving tickets
            return $tellers->map(function ($teller) use ($servingTickets) {
                return [
                    'teller' => $teller,
                    'ticket' => $servingTickets->get($teller->id)?->first(),
                ];
            })->toArray();
        });

        // Get upcoming tickets
        $this->upNext = Ticket::forToday()
            ->waiting()
            ->with('category:id,name,prefix')
            ->orderBy('id', 'asc')
            ->take(10)
            ->get();
    }

    public function render()
    {
        return view('livewire.display-monitor');
    }
}
