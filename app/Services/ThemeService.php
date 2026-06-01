<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;

class ThemeService
{
    const DARK_MODE = 'dark';
    const LIGHT_MODE = 'light';
    const SYSTEM_MODE = 'system';
    
    /**
     * Get user's theme preference
     */
    public static function getUserTheme(): string
    {
        if (Auth::check()) {
            // Get from user preferences in database
            $theme = Auth::user()->preferences['theme'] ?? self::SYSTEM_MODE;
            return $theme;
        }
        
        // Get from localStorage for guests
        return session('theme', self::SYSTEM_MODE);
    }

    /**
     * Set user's theme preference
     */
    public static function setUserTheme(string $theme): void
    {
        if (Auth::check()) {
            // Save to database
            $preferences = Auth::user()->preferences ?? [];
            $preferences['theme'] = $theme;
            Auth::user()->update(['preferences' => $preferences]);
        } else {
            // Save to session for guests
            session(['theme' => $theme]);
        }
    }

    /**
     * Get active theme (dark or light)
     */
    public static function getActiveTheme(): string
    {
        $theme = self::getUserTheme();

        if ($theme === self::SYSTEM_MODE) {
            // Return based on system preference (default to dark)
            return self::DARK_MODE;
        }

        return $theme;
    }

    /**
     * Get theme colors for current mode
     */
    public static function getThemeColors(string $mode = null): array
    {
        $mode = $mode ?? self::getActiveTheme();

        if ($mode === self::LIGHT_MODE) {
            return [
                'bg-primary' => 'bg-white',
                'bg-secondary' => 'bg-gray-50',
                'bg-tertiary' => 'bg-gray-100',
                'text-primary' => 'text-gray-900',
                'text-secondary' => 'text-gray-600',
                'border' => 'border-gray-200',
                'card' => 'bg-white border-gray-200',
            ];
        }

        // Dark mode
        return [
            'bg-primary' => 'bg-dark-bg',
            'bg-secondary' => 'bg-dark-card',
            'bg-tertiary' => 'bg-gray-800',
            'text-primary' => 'text-white',
            'text-secondary' => 'text-gray-400',
            'border' => 'border-gray-700',
            'card' => 'bg-dark-card border-gray-700',
        ];
    }
}