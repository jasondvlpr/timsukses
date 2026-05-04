<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Website;
use App\Models\WebsiteRequest;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'whatsapp' => ['required', 'string', 'max:20', 'unique:users,whatsapp'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'websites' => ['nullable', 'array', 'max:10'],
            'websites.*' => ['nullable', 'string', 'max:255'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'whatsapp' => $request->whatsapp,
            'password' => Hash::make($request->password),
            'role' => 'promoter', // Default role for new registrations
        ]);

        // Save websites if provided
        if ($request->has('websites')) {
            foreach ($request->websites as $url) {
                if (!empty($url)) {
                    $normalizedUrl = str_starts_with($url, 'http') ? $url : 'https://' . $url;
                    
                    // Check if already exists in either table
                    $existsInWebsites = Website::where('url', $normalizedUrl)->exists();
                    $existsInRequests = WebsiteRequest::where('url', $normalizedUrl)->exists();

                    if (!$existsInWebsites && !$existsInRequests) {
                        // Create as approved request so it shows in the dashboard list
                        WebsiteRequest::create([
                            'user_id' => $user->id,
                            'name' => $url,
                            'url' => $normalizedUrl,
                            'description' => 'Website yang sudah dimiliki saat mendaftar.',
                            'status' => 'approved',
                            'admin_note' => 'Disetujui otomatis dari pendaftaran.',
                        ]);

                        // Also create as active website
                        Website::create([
                            'user_id' => $user->id,
                            'name' => $url,
                            'url' => $normalizedUrl,
                            'is_active' => true,
                        ]);
                    }
                }
            }
        }

        event(new Registered($user));

        Auth::login($user);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Pendaftaran berhasil!',
                'redirect' => route('dashboard')
            ]);
        }

        return redirect(route('dashboard', absolute: false));
    }
}
