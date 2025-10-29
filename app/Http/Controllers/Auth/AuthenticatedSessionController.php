<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    
public function store(Request $request)
{
    $credentials = $request->validate([
        'login'    => ['required','string'],  // รับ username หรือ email
        'password' => ['required','string'],
    ]);

    $loginField = filter_var($credentials['login'], FILTER_VALIDATE_EMAIL)
        ? 'email'
        : 'username';

    $attempt = Auth::attempt(
        [$loginField => $credentials['login'], 'password' => $credentials['password']],
        $request->boolean('remember')
    );

    if (! $attempt) {
        throw ValidationException::withMessages([
            'login' => __('auth.failed'),
        ]);
    }

    $request->session()->regenerate();

    // กลับไปหน้าที่ตั้งใจ หรือไปโปรไฟล์
    return redirect()->intended(route('profile.show'));
}

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
