<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ThemeController extends Controller
{
    /**
     * Toggle the authenticated user's theme between dark and light.
     */
    public function toggle(Request $request)
    {
        $user = auth()->user();
        
        if ($user) {
            $user->update([
                'theme' => $user->theme === 'dark' ? 'light' : 'dark'
            ]);
        }

        return back();
    }
}
