<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Models\SchoolYear;
use Inertia\Response;
use App\Models\ClubManager;
use App\Models\ClubRegister;
use App\Models\LogRecord;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();
        // Store current school year in session
        LogRecord::create([
            'user_id' => auth()->user()->id,
            'log_type' => 'login',
        ]);
        if (auth()->user()?->role === 'club manager') {
            $schoolYear = SchoolYear::findOrFail(2);
        } else {
            $schoolYear = SchoolYear::active();
        }

        $schoolYear = SchoolYear::syncSession($schoolYear);
        Auth::user()->clubs = ClubRegister::where('school_year_id', $schoolYear->id)
            ->where('user_id', auth()->user()->id)
            ->with(['club', 'user', 'schoolYear', 'learners.currentEnrollment.section'])
            ->get();

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        LogRecord::create([
            'user_id' => auth()->user()->id,
            'log_type' => 'logout',
        ]);
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
