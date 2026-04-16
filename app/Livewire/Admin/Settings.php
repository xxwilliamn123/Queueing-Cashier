<?php

namespace App\Livewire\Admin;

use App\Models\Settings as SettingsModel;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

#[Layout('layouts.app')]
class Settings extends Component
{
    use WithFileUploads;

    /** @var list<string> */
    public const ALLOWED_THEMES = [
        'blue-theme',
        'light',
        'dark',
        'semi-dark',
        'bodered-theme',
    ];

    public $displayVideoUrl = '';
    public $displayMarqueeText = '';
    public $videoFile = null;
    public $useVideoFile = false;
    public $currentVideoPath = null;
    public $appTheme = 'blue-theme';

    public function mount()
    {
        $this->displayVideoUrl = SettingsModel::get('display_video_url', env('DISPLAY_VIDEO_URL', ''));
        $this->displayMarqueeText = SettingsModel::get('display_marquee_text', env('DISPLAY_MARQUEE_TEXT', 'Welcome to NORSU-GUIHULNGAN Queue System. Please wait for your number to be called.'));
        $this->currentVideoPath = SettingsModel::get('display_video_file', null);
        $this->useVideoFile = !empty($this->currentVideoPath);

        $storedTheme = SettingsModel::get('app_theme', 'blue-theme');
        $this->appTheme = in_array($storedTheme, self::ALLOWED_THEMES, true)
            ? $storedTheme
            : 'blue-theme';
    }

    protected $rules = [
        'displayVideoUrl' => 'nullable|url|max:500',
        'displayMarqueeText' => 'required|string|max:500',
        'videoFile' => 'nullable|mimes:mp4,webm,ogg|max:3145728', // 3GB max (supports 18.6MB files)
        'appTheme' => 'required|in:blue-theme,light,dark,semi-dark,bodered-theme',
    ];

    public function save()
    {
        $this->validate();

        // Handle video file upload
        if ($this->videoFile) {
            // Delete old video file if exists
            if ($this->currentVideoPath && Storage::disk('public')->exists($this->currentVideoPath)) {
                Storage::disk('public')->delete($this->currentVideoPath);
            }

            // Store new video file
            $videoPath = $this->videoFile->store('videos', 'public');
            SettingsModel::set('display_video_file', $videoPath, 'file');
            $this->currentVideoPath = $videoPath;
            $this->useVideoFile = true;
        }

        // Save video URL (only if not using uploaded file)
        if (!$this->useVideoFile) {
            SettingsModel::set('display_video_url', $this->displayVideoUrl, 'url');
            // Clear video file if switching to URL
            if ($this->currentVideoPath && Storage::disk('public')->exists($this->currentVideoPath)) {
                Storage::disk('public')->delete($this->currentVideoPath);
                SettingsModel::set('display_video_file', null, 'file');
                $this->currentVideoPath = null;
            }
        }

        // Save marquee text
        SettingsModel::set('display_marquee_text', $this->displayMarqueeText, 'text');
        SettingsModel::set('app_theme', $this->appTheme, 'text');

        $this->dispatch('toastr', ['type' => 'success', 'message' => 'Settings saved successfully!']);
    }

    public function deleteVideoFile()
    {
        if ($this->currentVideoPath && Storage::disk('public')->exists($this->currentVideoPath)) {
            Storage::disk('public')->delete($this->currentVideoPath);
        }
        SettingsModel::set('display_video_file', null, 'file');
        $this->currentVideoPath = null;
        $this->useVideoFile = false;
        $this->videoFile = null;
        
        $this->dispatch('toastr', ['type' => 'success', 'message' => 'Video file deleted successfully!']);
    }


    public function render()
    {
        return view('livewire.admin.settings');
    }
}
