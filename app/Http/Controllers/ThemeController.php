<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ThemeController extends Controller
{
    /**
     * Toggle between light and dark mode
     */
    public function toggleTheme(Request $request)
    {
        $theme = $request->input('theme', 'light');
        
        // Validate theme
        if (!in_array($theme, ['light', 'dark'])) {
            $theme = 'light';
        }

        // Save to session
        Session::put('theme', $theme);

        // If user is authenticated, save to database
        if (auth()->check()) {
            auth()->user()->update(['theme' => $theme]);
        }

        return response()->json([
            'success' => true,
            'theme' => $theme
        ]);
    }

    /**
     * Change font size
     */
    public function changeFontSize(Request $request)
    {
        $fontSize = $request->input('font_size', 'medium');
        
        // Validate font size
        if (!in_array($fontSize, ['small', 'medium', 'large'])) {
            $fontSize = 'medium';
        }

        // Save to session
        Session::put('font_size', $fontSize);

        // If user is authenticated, save to database
        if (auth()->check()) {
            auth()->user()->update(['font_size' => $fontSize]);
        }

        return response()->json([
            'success' => true,
            'font_size' => $fontSize
        ]);
    }

    /**
     * Change locale
     */
    public function changeLocale(Request $request, string $locale)
    {
        // Validate locale
        if (!in_array($locale, ['en', 'ne'])) {
            $locale = 'en';
        }

        // Save to session
        Session::put('locale', $locale);

        // If user is authenticated, save to database
        if (auth()->check()) {
            auth()->user()->update(['locale' => $locale]);
        }

        return redirect()->back()->with('success', 'Language changed successfully');
    }
}
