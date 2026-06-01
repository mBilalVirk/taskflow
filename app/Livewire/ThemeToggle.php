<?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\ThemeService;
use Illuminate\Support\Facades\Auth;

class ThemeToggle extends Component
{
    public string $currentTheme = 'dark';
    public bool $isDropdownOpen = false;

    public function mount()
    {
        $this->currentTheme = ThemeService::getUserTheme();
    }

    /**
     * Set theme preference
     */
    public function setTheme(string $theme)
    {
        // Validate theme
        $validThemes = ['dark', 'light', 'system'];
        if (!in_array($theme, $validThemes)) {
            return;
        }
        // \Log::info("Setting theme to: $theme for user: " . Auth::id());
        $this->currentTheme = $theme;
        ThemeService::setUserTheme($theme);
        
        // Dispatch event to update UI
        $this->dispatch('theme-changed', theme: $theme);

        // Close dropdown
        $this->isDropdownOpen = false;
    }

    /**
     * Toggle between dark and light
     */
    public function toggleTheme()
    {
        $newTheme = $this->currentTheme === 'dark' ? 'light' : 'dark';
        $this->setTheme($newTheme);
    }

    public function render()
    {
        return view('livewire.theme-toggle');
    }
}